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
        // Historial de pedidos - accesible para clientes registrados e invitados
        $orderModel = new Order();
        
        // Si el usuario está logueado, mostrar sus pedidos
        // Si es invitado, mostrar pedidos según el email de sesión (si existe)
        if (isset($_SESSION['user_email'])) {
            $userOrders = $orderModel->getByEmail($_SESSION['user_email']);
        } else {
            $userOrders = [];
        }
        
        include __DIR__ . '/../views/order-history.php';
    }
}
?>