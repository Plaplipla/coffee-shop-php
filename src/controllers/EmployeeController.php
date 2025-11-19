<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';

class EmployeeController {
    private $orderModel;
    private $productModel;
    private $LOW_STOCK_THRESHOLD = 10; //Umbral de bajo stock
    
    public function __construct() {
        $this->orderModel = new Order();
        $this->productModel = new Product();
    }
    
    public function orders() {
        if (!isset($_SESSION['user_id']) || 
            ($_SESSION['user_role'] !== 'empleado' && 
            $_SESSION['user_role'] !== 'trabajador' && 
            $_SESSION['user_role'] !== 'administrador')) {
            $_SESSION['error'] = 'Acceso denegado. Se requiere rol de empleado.';
            header('Location: /home');
            exit;
        }
        
        $status = $_GET['status'] ?? 'all';
        $orders = [];
        
        if ($status === 'active') {
            $orders = array_merge(
                $this->orderModel->getByStatus('pending') ?? [],
                $this->orderModel->getByStatus('preparing') ?? []
            );
        } elseif ($status === 'all') {
            $orders = $this->orderModel->getAll();
        } else {
            $orders = $this->orderModel->getByStatus($status);
        }
        
        // Obtener todos los productos
        $allProducts = $this->productModel->getAll();
        
        // Detectar productos con bajo stock
        $lowStockProducts = [];
        foreach ($allProducts as $product) {
            $stock = 0;
            if (is_array($product)) {
                $stock = isset($product['stock']) ? intval($product['stock']) : 0;
            } elseif (is_object($product)) {
                $stock = isset($product->stock) ? intval($product->stock) : 0;
            }

            if ($stock < $this->LOW_STOCK_THRESHOLD) {
                $lowStockProducts[] = $product;
            }
        }
        
        require __DIR__ . '/../views/employee/orders.php';
    }
    
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $newStatus = $_POST['status'] ?? null;
            
            if (!$orderId || !$newStatus) {
                $_SESSION['error'] = 'Datos inválidos.';
                header('Location: /employee/orders');
                exit;
            }
            
            try {
                $result = $this->orderModel->updateStatus($orderId, $newStatus);
                
                if ($result) {
                    $_SESSION['success'] = 'Estado del pedido actualizado correctamente.';
                } else {
                    $_SESSION['error'] = 'Error al actualizar el estado del pedido.';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error: ' . $e->getMessage();
            }
            
            header('Location: /employee/orders');
            exit;
        }
    }
}
?>
