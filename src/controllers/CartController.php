<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';

class CartController {
    private $productModel;
    private $orderModel;
    // Mapa de precios de extras (CLP) usado para recalcular en servidor
    private $extrasPriceMap = [
        'descafeinado' => 1000,
        'extraShot' => 990,
        'syrupVainilla' => 990,
        'syrupChocolate' => 990,
    ];
    
    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = intval($_POST['quantity'] ?? 1);
            $productPrice = intval($_POST['product_price'] ?? 0);
            $extras = $_POST['extras'] ?? '{}';
            
            $product = $this->productModel->getProductById($productId);
            if (!$product) {
                $_SESSION['error'] = 'Producto no encontrado';
                header('Location: /home');
                exit;
            }
            
            $this->addToSessionCart($product, $quantity, $productPrice, $extras);
            $_SESSION['success'] = 'Producto agregado al carrito';
            
            header('Location: ' . ($_POST['return_url'] ?? '/home'));
            exit;
        }
    }
    
    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItemKey = $_POST['cart_item_key'];
            $this->removeFromSessionCart($cartItemKey);
            $_SESSION['success'] = 'Producto eliminado del carrito';
            
            header('Location: /cart');
            exit;
        }
    }

    /**
     * Eliminar un extra específico de un item del carrito
     */
    public function removeExtra() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItemKey = $_POST['cart_item_key'] ?? '';
            $extraId = $_POST['extra_id'] ?? '';

            $this->initSessionCart();
            if ($cartItemKey && $extraId && isset($_SESSION['cart'][$cartItemKey])) {
                $item = $_SESSION['cart'][$cartItemKey];
                $extras = json_decode($item['extras'] ?? '{}', true);
                if (!is_array($extras)) { $extras = []; }
                // Poner cantidad del extra a 0 (eliminar)
                if (isset($extras[$extraId])) {
                    $extras[$extraId] = 0;
                }
                // Guardar y recalcular precio unitario
                $item['extras'] = json_encode($extras);
                $item['price'] = $this->recalculateItemUnitPrice($item);
                
                // Generar nueva clave con extras actualizados
                $newCartKey = $item['product_id'] . '_' . md5($item['extras']);
                
                // Si la nueva clave es diferente y ya existe otro item con la misma config, consolidar
                if ($newCartKey !== $cartItemKey && isset($_SESSION['cart'][$newCartKey])) {
                    // Sumar cantidades al item existente
                    $_SESSION['cart'][$newCartKey]['quantity'] += $item['quantity'];
                    // Eliminar el item antiguo
                    unset($_SESSION['cart'][$cartItemKey]);
                    $_SESSION['success'] = 'Extra eliminado y productos consolidados';
                } else if ($newCartKey !== $cartItemKey) {
                    // Cambiar clave del item (no hay duplicado)
                    unset($_SESSION['cart'][$cartItemKey]);
                    $item['cart_item_key'] = $newCartKey;
                    $_SESSION['cart'][$newCartKey] = $item;
                    $_SESSION['success'] = 'Extra eliminado y precio actualizado';
                } else {
                    // La clave no cambió, solo actualizar
                    $_SESSION['cart'][$cartItemKey] = $item;
                    $_SESSION['success'] = 'Extra eliminado y precio actualizado';
                }
            } else {
                $_SESSION['error'] = 'No se pudo eliminar el extra';
            }
            header('Location: /cart');
            exit;
        }
    }
    
    public function updateQuantity() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItemKey = $_POST['cart_item_key'];
            $quantity = intval($_POST['quantity']);
            
            $this->updateSessionCartQuantity($cartItemKey, $quantity);
            
            header('Location: /cart');
            exit;
        }
    }
    
    public function view() {
        $cartItems = $this->getSessionCartItems();
        $cartTotal = $this->getSessionCartTotal();
        $itemCount = $this->getSessionCartItemCount();
        
        require __DIR__ . '/../views/cart.php';
    }
    
    public function clear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->clearSessionCart();
            $_SESSION['success'] = 'Carrito vaciado';
            
            header('Location: /cart');
            exit;
        }
    }
    
    public function checkout() {
        $cartItems = $this->getSessionCartItems();
        $cartTotal = $this->getSessionCartTotal();
        
        if (empty($cartItems)) {
            $_SESSION['error'] = 'Tu carrito está vacío';
            header('Location: /cart');
            exit;
        }
        // Determinar si el usuario (si está logueado) es su primer pedido
        $isFirstOrder = true;
        if (isset($_SESSION['user_email'])) {
            $existing = $this->orderModel->getByEmail($_SESSION['user_email']);
            $isFirstOrder = empty($existing);
        }

        require __DIR__ . '/../views/checkout.php';
    }
    
    public function processOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartItems = $this->getSessionCartItems();
            
            if (empty($cartItems)) {
                $_SESSION['error'] = 'Tu carrito está vacío';
                header('Location: /cart');
                exit;
            }

            // GUARDAR PEDIDO REAL EN BASE DE DATOS
            $deliveryType = $_POST['delivery_type'] ?? 'delivery';
            $deliveryAddress = $_POST['delivery_address'] ?? '';
            $deliveryFee = 0;
            if ($deliveryType === 'delivery') {
                $deliveryFee = 3000;
            }

            // Procesar descuento y datos del cliente
            $discountCode = isset($_POST['discount_code']) ? trim(strtoupper($_POST['discount_code'])) : '';
            $discountPercentage = isset($_POST['discount_percentage']) ? intval($_POST['discount_percentage']) : 0;
            $discountAmount = 0;
            $paymentMethod = $_POST['payment_method'] ?? 'card';
            
            // Si el método de pago es Stripe, redirigir al flujo de pago online
            if ($paymentMethod === 'stripe') {
                $this->processStripePayment();
                return;
            }

            // Obtener email del cliente (para validar primer pedido)
            $customerEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ($_POST['customer_email'] ?? '');

            // Validar y aplicar descuento: solo aplicar si el código y el porcentaje coinciden
            // y el cliente no tiene pedidos previos (primer pedido)
            if ($discountCode === 'WELCOME15' && $discountPercentage === 15) {
                $existingOrders = $this->orderModel->getByEmail($customerEmail);
                if (empty($existingOrders)) {
                    // Es primer pedido, aplicar descuento sobre el subtotal (solo productos)
                    $subtotal = $this->getSessionCartTotal();
                    $discountAmount = round($subtotal * ($discountPercentage / 100), 2);
                } else {
                    // No es elegible: eliminar el código para que la confirmación no muestre descuento
                    $discountCode = '';
                    $discountAmount = 0;
                }
            } elseif (!empty($discountCode)) {
                $discountCode = '';
                $discountAmount = 0;
            }

            // Generar número de orden usando el método del modelo
            $orderNumber = $this->orderModel->generateOrderNumber();

            // Calcular subtotal y total finales
            $subtotal = $this->getSessionCartTotal();
            $total = $subtotal + $deliveryFee - $discountAmount;

            $orderData = [
                'order_number' => $orderNumber,
                'customer_name' => $_POST['customer_name'],
                'customer_email' => $customerEmail,
                'customer_phone' => $_POST['customer_phone'],
                'delivery_type' => $deliveryType,
                'delivery_address' => $deliveryAddress,
                'delivery_address_normalized' => $_POST['delivery_address_normalized'] ?? null,
                'delivery_lat' => isset($_POST['delivery_lat']) && $_POST['delivery_lat'] !== '' ? floatval($_POST['delivery_lat']) : null,
                'delivery_lng' => isset($_POST['delivery_lng']) && $_POST['delivery_lng'] !== '' ? floatval($_POST['delivery_lng']) : null,
                'delivery_fee' => $deliveryFee,
                'items' => $cartItems,
                'subtotal' => $subtotal,
                'discount_code' => $discountCode,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'order_date' => date('Y-m-d H:i:s'),
                'status' => 'pending'
            ];
            
            $result = $this->orderModel->create($orderData);
            
            if ($result) {
            // Limpiar carrito después del pedido
                $this->clearSessionCart();
            
                // Guardar datos del pedido en sesión para mostrar en confirmación
                $_SESSION['order_data'] = $orderData;
                $_SESSION['success'] = '🎉 ¡Pedido realizado con éxito!';
                
                header('Location: /cart/order-confirmation');
                exit;
            } else {
                $_SESSION['error'] = 'Hubo un error al procesar tu pedido. Por favor, intenta nuevamente.';
                header('Location: /checkout');
                exit;
            }
        }
    }
    
    public function orderConfirmation() {
        // Mostrar página de confirmación del pedido
        if (!isset($_SESSION['order_data'])) {
            $_SESSION['error'] = 'No hay datos de pedido disponibles';
            header('Location: /cart');
            exit;
        }
        
        $orderData = $_SESSION['order_data'];
        require __DIR__ . '/../views/order-confirmation.php';
    }

    /**
     * Endpoint AJAX: verificar si un email es elegible para el descuento de primer pedido
     * Responde JSON: { ok: true, eligible: true|false }
     */
    public function checkEmail() {
        $email = trim($_GET['email'] ?? $_POST['email'] ?? '');
        header('Content-Type: application/json');

        if (empty($email)) {
            echo json_encode(['ok' => false, 'eligible' => false]);
            exit;
        }

        $existing = $this->orderModel->getByEmail($email);

        $eligible = empty($existing);
        echo json_encode(['ok' => true, 'eligible' => $eligible]);
        exit;
    }
    
    // ==================== MÉTODOS DE SESIÓN ====================
    
    private function initSessionCart() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    
    private function addToSessionCart($product, $quantity = 1, $productPrice = 0, $extras = '{}') {
        $this->initSessionCart();
        
        $productId = (string)$product->_id;
        $productName = $product->name ?? '';
        $basePrice = $product->price ?? 0;
        $productImage = $product->image ?? '';
        $productDescription = $product->description ?? '';
        $productIcon = $product->icon ?? 'bi bi-cup-hot';
        
        if (is_array($product)) {
            $productId = (string)$product['_id'];
            $productName = $product['name'] ?? '';
            $basePrice = $product['price'] ?? 0;
            $productImage = $product['image'] ?? '';
            $productDescription = $product['description'] ?? '';
            $productIcon = $product['icon'] ?? 'bi bi-cup-hot';
        }
        
        $finalPrice = ($productPrice > 0) ? $productPrice : $basePrice;
        
        // Crear clave única: product_id + hash de extras
        $cartItemKey = $productId . '_' . md5($extras);
        
        if (isset($_SESSION['cart'][$cartItemKey])) {
            $_SESSION['cart'][$cartItemKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$cartItemKey] = [
                'cart_item_key' => $cartItemKey,
                'product_id' => $productId,
                'name' => $productName,
                'price' => $finalPrice,
                'base_price' => $basePrice,
                'quantity' => $quantity,
                'image' => $productImage,
                'description' => $productDescription,
                'icon' => $productIcon,
                'extras' => $extras
            ];
            // Asegurar price coherente en caso de que extras vengan en JSON
            $_SESSION['cart'][$cartItemKey]['price'] = $this->recalculateItemUnitPrice($_SESSION['cart'][$cartItemKey]);
        }
    }
    
    private function removeFromSessionCart($productId) {
        $this->initSessionCart();
        unset($_SESSION['cart'][$productId]);
    }
    
    private function updateSessionCartQuantity($productId, $quantity) {
        $this->initSessionCart();
        
        if ($quantity <= 0) {
            $this->removeFromSessionCart($productId);
            return;
        }
        
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        }
    }
    
    private function getSessionCartItems() {
        $this->initSessionCart();
        return $_SESSION['cart'];
    }
    
    private function getSessionCartTotal() {
        $items = $this->getSessionCartItems();
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }

    /**
     * Recalcular precio unitario a partir de base_price y extras
     */
    private function recalculateItemUnitPrice($item) {
        $base = isset($item['base_price']) ? intval($item['base_price']) : intval($item['price']);
        $extras = json_decode($item['extras'] ?? '{}', true);
        if (!is_array($extras)) { $extras = []; }
        $extrasTotal = 0;
        foreach ($extras as $key => $qty) {
            $qty = intval($qty);
            if ($qty > 0 && isset($this->extrasPriceMap[$key])) {
                $extrasTotal += $this->extrasPriceMap[$key] * $qty;
            }
        }
        return $base + $extrasTotal;
    }
    
    private function getSessionCartItemCount() {
        $items = $this->getSessionCartItems();
        $count = 0;
        
        foreach ($items as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }
    
    private function clearSessionCart() {
        $_SESSION['cart'] = [];
    }
    
    /**
     * Procesar pago con Stripe
     * Prepara los datos del pedido y redirige a la creación de sesión de Stripe
     */
    private function processStripePayment() {
        $cartItems = $this->getSessionCartItems();
        
        // Preparar datos del pedido
        $deliveryType = $_POST['delivery_type'] ?? 'delivery';
        $deliveryAddress = $_POST['delivery_address'] ?? '';
        $deliveryFee = 0;
        if ($deliveryType === 'delivery') {
            $deliveryFee = 3000;
        }
        
        // Procesar descuento
        $discountCode = isset($_POST['discount_code']) ? trim(strtoupper($_POST['discount_code'])) : '';
        $discountPercentage = isset($_POST['discount_percentage']) ? intval($_POST['discount_percentage']) : 0;
        $discountAmount = 0;
        
        // Obtener email del cliente
        $customerEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ($_POST['customer_email'] ?? '');
        
        // Validar descuento WELCOME15
        if ($discountCode === 'WELCOME15' && $discountPercentage === 15) {
            $existingOrders = $this->orderModel->getByEmail($customerEmail);
            if (empty($existingOrders)) {
                $subtotal = $this->getSessionCartTotal();
                $discountAmount = round($subtotal * ($discountPercentage / 100), 2);
            } else {
                $discountCode = '';
                $discountAmount = 0;
            }
        } elseif (!empty($discountCode)) {
            $discountCode = '';
            $discountAmount = 0;
        }
        
        // Generar número de orden
        $orderNumber = $this->orderModel->generateOrderNumber();
        
        // Calcular totales
        $subtotal = $this->getSessionCartTotal();
        $total = $subtotal + $deliveryFee - $discountAmount;
        
        // Preparar datos del pedido para guardar en sesión (pendiente de pago)
        $orderData = [
            'order_number' => $orderNumber,
            'customer_name' => $_POST['customer_name'],
            'customer_email' => $customerEmail,
            'customer_phone' => $_POST['customer_phone'],
            'delivery_type' => $deliveryType,
            'delivery_address' => $deliveryAddress,
            'delivery_address_normalized' => $_POST['delivery_address_normalized'] ?? null,
            'delivery_lat' => isset($_POST['delivery_lat']) && $_POST['delivery_lat'] !== '' ? floatval($_POST['delivery_lat']) : null,
            'delivery_lng' => isset($_POST['delivery_lng']) && $_POST['delivery_lng'] !== '' ? floatval($_POST['delivery_lng']) : null,
            'delivery_fee' => $deliveryFee,
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'discount_code' => $discountCode,
            'discount_amount' => $discountAmount,
            'total' => $total,
            'payment_method' => 'stripe',
            'order_date' => date('Y-m-d H:i:s'),
            'status' => 'pending_payment'
        ];
        
        // Guardar en sesión para recuperar después del pago
        $_SESSION['pending_order'] = $orderData;
        
        // Redirigir al controlador de pagos para crear sesión de Stripe
        header('Location: /payment/create-checkout');
        exit;
    }
}
?>