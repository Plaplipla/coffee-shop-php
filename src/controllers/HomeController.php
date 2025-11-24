<?php
class HomeController {
    
    public function index() {
        include __DIR__ . '/../views/home.php';
    }
    
    public function menu() {
        $productModel = new Product();
        $products = $productModel->getAll();
        
        include __DIR__ . '/../views/menu.php';
    }
    
    public function about() {
        include __DIR__ . '/../views/about.php';
    }
    
    public function orders() {
        // Historial de pedidos del cliente autenticado
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'cliente') {
            $_SESSION['error'] = 'Acceso denegado. Debes estar registrado como cliente.';
            header('Location: /home');
            exit;
        }
        
        $orderModel = new Order();
        $userOrders = $orderModel->getByEmail($_SESSION['user_email']);
        
        include __DIR__ . '/../views/order-history.php';
    }
}
?>