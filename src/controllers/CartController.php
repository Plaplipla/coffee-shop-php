<?php
require_once __DIR__ . '/../models/Product.php';

class CartController {
    private $productModel;
    
    public function __construct() {
        $this->productModel = new Product();
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
            require_once __DIR__ . '/../models/Order.php';
            $orderModel = new Order();
            
            $deliveryType = $_POST['delivery_type'] ?? 'delivery';
            $deliveryAddress = $_POST['delivery_address'] ?? '';
            $deliveryFee = 0;
            if ($deliveryType === 'delivery') {
                $deliveryFee = 3000;
            }

            // Generar número de orden usando el método del modelo
            $orderNumber = $orderModel->generateOrderNumber();

            $orderData = [
                'order_number' => $orderNumber,
                'customer_name' => $_POST['customer_name'],
                'customer_email' => $_POST['customer_email'],
                'customer_phone' => $_POST['customer_phone'],
                'delivery_type' => $deliveryType,
                'delivery_address' => $deliveryAddress,
                'delivery_fee' => $deliveryFee,
                'items' => $cartItems,
                'total' => $this->getSessionCartTotal() + $deliveryFee,
                'order_date' => date('Y-m-d H:i:s'),
                'status' => 'pending'
            ];
            
            $result = $orderModel->create($orderData);
            
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
}
?>