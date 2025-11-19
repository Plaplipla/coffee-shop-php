<?php
require_once __DIR__ . '/../models/Order.php';

class TrackingController {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new Order();
    }
    
    public function trackOrder() {
        $order = null;
        $notFound = false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderNumber = trim($_POST['order_number'] ?? '');
            
            if ($orderNumber) {
                // Buscar el pedido por número de orden
                $allOrders = $this->orderModel->getAll();
                
                error_log('DEBUG: Buscando orden: ' . $orderNumber);
                error_log('DEBUG: Total de órdenes en DB: ' . count($allOrders));
                
                foreach ($allOrders as $o) {
                    // Manejar tanto arrays como objetos
                    $oNum = null;
                    if (is_array($o)) {
                        $oNum = isset($o['order_number']) ? $o['order_number'] : '';
                    } else if (is_object($o)) {
                        $oNum = isset($o->order_number) ? $o->order_number : '';
                    }
                    
                    error_log('DEBUG: Comparando "' . $orderNumber . '" con "' . $oNum . '"');
                    
                    // Comparación exacta
                    if (!empty($oNum) && strtoupper($oNum) === strtoupper($orderNumber)) {
                        error_log('DEBUG: ¡Orden encontrada!');
                        if (is_array($o)) {
                            $order = (object)$o;
                        } else {
                            $order = $o;
                        }
                        break;
                    }
                }
                
                if ($order === null) {
                    $notFound = true;
                    error_log('Orden no encontrada: ' . $orderNumber);
                    error_log('Todas las órdenes disponibles:');
                    foreach ($allOrders as $o) {
                        $oNum = null;
                        if (is_array($o)) {
                            $oNum = $o['order_number'] ?? 'SIN NUMERO';
                        } else if (is_object($o)) {
                            $oNum = $o->order_number ?? 'SIN NUMERO';
                        }
                        error_log('  - ' . $oNum);
                    }
                }
            }
        }
        
        require __DIR__ . '/../views/track-order.php';
    }
}
?>
