<?php
require_once __DIR__ . '/../models/Order.php';

class DeliveryController {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new Order();
    }
    
    public function orders() {
        // Verificar acceso de repartidor
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'repartidor') {
            $_SESSION['error'] = 'Acceso denegado. Se requiere rol de repartidor.';
            header('Location: /home');
            exit;
        }
        
        $status = $_GET['status'] ?? 'ready';
        $orders = [];
        
        // Obtener solo pedidos de tipo delivery
        if ($status === 'ready') {
            // Pedidos listos para recoger
            $allReady = $this->orderModel->getByStatus('ready') ?? [];
            $orders = array_filter($allReady, function($order) {
                $order = (array)$order; // Convertir objeto a array
                return isset($order['delivery_type']) && $order['delivery_type'] === 'delivery';
            });
        } elseif ($status === 'in-transit') {
            // Pedidos en camino
            $allInTransit = $this->orderModel->getByStatus('in-transit') ?? [];
            $orders = array_filter($allInTransit, function($order) {
                $order = (array)$order;
                return isset($order['delivery_type']) && $order['delivery_type'] === 'delivery';
            });
        } elseif ($status === 'delivered') {
            // Pedidos entregados (últimos 20)
            $allDelivered = $this->orderModel->getByStatus('delivered') ?? [];
            $deliveredOrders = array_filter($allDelivered, function($order) {
                $order = (array)$order;
                return isset($order['delivery_type']) && $order['delivery_type'] === 'delivery';
            });
            $orders = array_slice($deliveredOrders, 0, 20);
        } elseif ($status === 'all') {
            // Todos los pedidos de delivery
            $allOrders = $this->orderModel->getAll() ?? [];
            $orders = array_filter($allOrders, function($order) {
                $order = (array)$order;
                return isset($order['delivery_type']) && $order['delivery_type'] === 'delivery';
            });
        }
        
        // Calcular estadísticas
        $stats = [
            'ready' => 0,
            'in_transit' => 0,
            'delivered_today' => 0
        ];
        
        $allOrders = $this->orderModel->getAll() ?? [];
        $today = date('Y-m-d');
        
        foreach ($allOrders as $order) {
            $order = (array)$order; // Convertir objeto a array
            if (!isset($order['delivery_type']) || $order['delivery_type'] !== 'delivery') continue;
            
            if ($order['status'] === 'ready') {
                $stats['ready']++;
            } elseif ($order['status'] === 'in-transit') {
                $stats['in_transit']++;
            } elseif ($order['status'] === 'delivered') {
                $orderDate = null;
                
                // Manejar fecha de MongoDB UTCDateTime
                if (isset($order['updated_at'])) {
                    if (is_object($order['updated_at']) && method_exists($order['updated_at'], 'toDateTime')) {
                        $orderDate = $order['updated_at']->toDateTime()->format('Y-m-d');
                    } else {
                        $orderDate = date('Y-m-d', strtotime($order['updated_at']));
                    }
                } elseif (isset($order['created_at'])) {
                    if (is_object($order['created_at']) && method_exists($order['created_at'], 'toDateTime')) {
                        $orderDate = $order['created_at']->toDateTime()->format('Y-m-d');
                    } else {
                        $orderDate = date('Y-m-d', strtotime($order['created_at']));
                    }
                }
                
                if ($orderDate === $today) {
                    $stats['delivered_today']++;
                }
            }
        }
        
        require __DIR__ . '/../views/delivery/orders.php';
    }
    
    public function updateStatus() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'repartidor') {
            echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $orderId = $_POST['order_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;
        
        if (!$orderId || !$newStatus) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }
        
        // Validar que solo pueda actualizar a in-transit o delivered
        if (!in_array($newStatus, ['in-transit', 'delivered'])) {
            echo json_encode(['success' => false, 'message' => 'Estado inválido']);
            return;
        }
        
        // Verificar que el pedido sea de tipo delivery
        $order = $this->orderModel->getById($orderId);
        $order = (array)$order; // Convertir objeto a array
        if (!$order || !isset($order['delivery_type']) || $order['delivery_type'] !== 'delivery') {
            echo json_encode(['success' => false, 'message' => 'Pedido no encontrado o no es delivery']);
            return;
        }
        
        $result = $this->orderModel->updateStatus($orderId, $newStatus);
        
        if ($result) {
            $statusText = $newStatus === 'in-transit' ? 'En Camino' : 'Entregado';
            echo json_encode([
                'success' => true, 
                'message' => "Pedido marcado como {$statusText}"
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar estado']);
        }
    }
}
