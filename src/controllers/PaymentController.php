<?php
require_once __DIR__ . '/../models/Order.php';

/**
 * PaymentController - Maneja pagos con Stripe
 */
class PaymentController {
    private $orderModel;
    private $stripeConfig;
    private $secretKey;
    private $publicKey;
    
    public function __construct() {
        $this->orderModel = new Order();
        $this->loadStripeConfig();
    }
    
    private function loadStripeConfig() {
        $this->stripeConfig = require __DIR__ . '/../config/stripe.php';
        $mode = $this->stripeConfig['mode'] ?? 'test';
        
        if ($mode === 'live') {
            $this->secretKey = $this->stripeConfig['live_secret_key'];
            $this->publicKey = $this->stripeConfig['live_public_key'];
        } else {
            $this->secretKey = $this->stripeConfig['test_secret_key'];
            $this->publicKey = $this->stripeConfig['test_public_key'];
        }
    }
    
    /**
     * Crear una sesión de Checkout de Stripe
     */
    public function createCheckoutSession() {
        // Obtener datos del carrito desde la sesión
        if (!isset($_SESSION['pending_order'])) {
            $_SESSION['error'] = 'No hay datos de pedido disponibles. Por favor, intenta nuevamente.';
            header('Location: /checkout');
            exit;
        }
        
        $orderData = $_SESSION['pending_order'];
        
        // Preparar line items para Stripe
        $lineItems = [];
        foreach ($orderData['items'] as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $this->stripeConfig['currency'],
                    'product_data' => [
                        'name' => $item['name'],
                        'description' => $item['description'] ?? '',
                    ],
                    'unit_amount' => intval($item['price'] * 100), // Stripe usa centavos
                ],
                'quantity' => $item['quantity'],
            ];
        }
        
        // Agregar costo de envío si existe
        if ($orderData['delivery_fee'] > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $this->stripeConfig['currency'],
                    'product_data' => [
                        'name' => 'Envío a domicilio',
                    ],
                    'unit_amount' => intval($orderData['delivery_fee'] * 100),
                ],
                'quantity' => 1,
            ];
        }
        
        // Agregar descuento si existe
        $discounts = [];
        if (isset($orderData['discount_amount']) && $orderData['discount_amount'] > 0) {
            // Crear cupón en Stripe
            $couponId = $this->createStripeCoupon($orderData['discount_code'], $orderData['discount_amount']);
            if ($couponId) {
                $discounts[] = ['coupon' => $couponId];
            }
        }
        
        // Construir URL base
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . '://' . $host;
        
        // Datos para crear la sesión de Checkout
        $sessionData = [
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $baseUrl . $this->stripeConfig['success_url'],
            'cancel_url' => $baseUrl . $this->stripeConfig['cancel_url'],
            'customer_email' => $orderData['customer_email'],
            'client_reference_id' => $orderData['order_number'],
            'metadata' => [
                'order_number' => $orderData['order_number'],
                'customer_name' => $orderData['customer_name'],
                'customer_phone' => $orderData['customer_phone'],
            ],
        ];
        
        if (!empty($discounts)) {
            $sessionData['discounts'] = $discounts;
        }
        
        // Crear sesión usando API REST de Stripe
        $session = $this->stripeApiRequest('POST', '/v1/checkout/sessions', $sessionData);
        
        if (isset($session['error'])) {
            $_SESSION['error'] = 'Error al crear sesión de pago: ' . $session['error']['message'];
            header('Location: /checkout');
            exit;
        }
        
        // Guardar session_id en la sesión para verificación posterior
        $_SESSION['stripe_session_id'] = $session['id'];
        
        // Redirigir a Stripe Checkout
        header('Location: ' . $session['url']);
        exit;
    }
    
    /**
     * Página de éxito después del pago
     */
    public function success() {
        $sessionId = $_GET['session_id'] ?? null;
        
        if (!$sessionId) {
            $_SESSION['error'] = 'Sesión de pago no válida';
            header('Location: /cart');
            exit;
        }
        
        // Obtener información de la sesión de Stripe
        $session = $this->stripeApiRequest('GET', '/v1/checkout/sessions/' . $sessionId);
        
        if (isset($session['error'])) {
            $_SESSION['error'] = 'Error al verificar el pago';
            header('Location: /cart');
            exit;
        }
        
        // Verificar que el pago fue exitoso
        if ($session['payment_status'] === 'paid') {
            // Recuperar datos del pedido pendiente
            if (isset($_SESSION['pending_order'])) {
                $orderData = $_SESSION['pending_order'];
                
                // Actualizar el estado del pedido a 'paid'
                $orderData['status'] = 'paid';
                $orderData['stripe_session_id'] = $sessionId;
                $orderData['stripe_payment_intent'] = $session['payment_intent'] ?? null;
                
                // Guardar el pedido en la base de datos
                $result = $this->orderModel->create($orderData);
                
                if ($result) {
                    // Limpiar el carrito y datos pendientes
                    unset($_SESSION['pending_order']);
                    unset($_SESSION['stripe_session_id']);
                    
                    // Guardar datos para mostrar en confirmación
                    $_SESSION['order_data'] = $orderData;
                    $_SESSION['success'] = '🎉 ¡Pago completado con éxito!';
                    
                    // Mostrar página de éxito
                    require __DIR__ . '/../views/payment-success.php';
                    exit;
                }
            }
        }
        
        $_SESSION['error'] = 'No se pudo completar el pedido';
        header('Location: /cart');
        exit;
    }
    
    /**
     * Página de cancelación
     */
    public function cancel() {
        $_SESSION['error'] = 'Pago cancelado. Tu carrito sigue disponible.';
        require __DIR__ . '/../views/payment-cancel.php';
    }
    
    /**
     * Webhook para recibir eventos de Stripe
     */
    public function webhook() {
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        
        // Verificar firma del webhook
        if (!$this->verifyWebhookSignature($payload, $sigHeader)) {
            http_response_code(400);
            exit('Webhook signature verification failed');
        }
        
        $event = json_decode($payload, true);
        
        // Manejar diferentes tipos de eventos
        switch ($event['type']) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event['data']['object']);
                break;
                
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event['data']['object']);
                break;
                
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event['data']['object']);
                break;
        }
        
        http_response_code(200);
        exit('Webhook received');
    }
    
    /**
     * Manejar evento de sesión completada
     */
    private function handleCheckoutSessionCompleted($session) {
        $orderNumber = $session['client_reference_id'] ?? null;
        
        if ($orderNumber) {
            // Buscar el pedido en la base de datos y actualizar su estado
            $orders = $this->orderModel->getAll(['order_number' => $orderNumber]);
            
            if (!empty($orders)) {
                $order = $orders[0];
                $this->orderModel->updateStatus($order->_id, 'paid');
            }
        }
    }
    
    /**
     * Manejar evento de pago exitoso
     */
    private function handlePaymentIntentSucceeded($paymentIntent) {
        error_log('Payment intent succeeded: ' . $paymentIntent['id']);
    }
    
    /**
     * Manejar evento de pago fallido
     */
    private function handlePaymentIntentFailed($paymentIntent) {
        error_log('Payment intent failed: ' . $paymentIntent['id']);
    }
    
    /**
     * Verificar firma del webhook
     */
    private function verifyWebhookSignature($payload, $sigHeader) {
        $webhookSecret = $this->stripeConfig['webhook_secret'];
        
        // Si no hay secret configurado, no verificar (solo en desarrollo)
        if (empty($webhookSecret) || $webhookSecret === 'whsec_TU_WEBHOOK_SECRET') {
            return true;
        }
        
        // Extraer timestamp y firma
        $parts = explode(',', $sigHeader);
        $timestamp = null;
        $signature = null;
        
        foreach ($parts as $part) {
            list($key, $value) = explode('=', trim($part), 2);
            if ($key === 't') {
                $timestamp = $value;
            } elseif ($key === 'v1') {
                $signature = $value;
            }
        }
        
        if (!$timestamp || !$signature) {
            return false;
        }
        
        // Construir string firmado
        $signedPayload = $timestamp . '.' . $payload;
        
        // Calcular firma esperada
        $expectedSignature = hash_hmac('sha256', $signedPayload, $webhookSecret);
        
        // Comparar firmas
        return hash_equals($expectedSignature, $signature);
    }
    
    /**
     * Crear cupón en Stripe para descuentos
     */
    private function createStripeCoupon($code, $amount) {
        // Convertir a centavos
        $amountOff = intval($amount * 100);
        
        $couponData = [
            'id' => strtoupper($code) . '_' . time(),
            'amount_off' => $amountOff,
            'currency' => $this->stripeConfig['currency'],
            'duration' => 'once',
        ];
        
        $coupon = $this->stripeApiRequest('POST', '/v1/coupons', $couponData);
        
        if (isset($coupon['error'])) {
            return null;
        }
        
        return $coupon['id'];
    }
    
    /**
     * Hacer request a la API de Stripe usando cURL
     */
    private function stripeApiRequest($method, $endpoint, $data = []) {
        $url = 'https://api.stripe.com' . $endpoint;
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secretKey . ':');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildQueryString($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if ($httpCode >= 400) {
            return ['error' => $result['error'] ?? ['message' => 'Unknown error']];
        }
        
        return $result;
    }
    
    /**
     * Construir query string para datos anidados de Stripe
     */
    private function buildQueryString($data, $prefix = '') {
        $result = [];
        
        foreach ($data as $key => $value) {
            $fullKey = $prefix ? $prefix . '[' . $key . ']' : $key;
            
            if (is_array($value)) {
                $result[] = $this->buildQueryString($value, $fullKey);
            } else {
                $result[] = urlencode($fullKey) . '=' . urlencode($value);
            }
        }
        
        return implode('&', $result);
    }
    
    /**
     * Obtener clave pública para el frontend
     */
    public function getPublicKey() {
        header('Content-Type: application/json');
        echo json_encode(['publicKey' => $this->publicKey]);
        exit;
    }
}
?>
