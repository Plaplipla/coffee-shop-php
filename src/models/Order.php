<?php
require_once __DIR__ . '/../core/Database.php';

class Order {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($orderData) {
        $orderData['order_number'] = $this->generateOrderNumber();
        $orderData['status'] = 'pending';
        $orderData['created_at'] = new MongoDB\BSON\UTCDateTime();
        
        return $this->db->insert('orders', $orderData);
    }
    
    public function getAll($filters = []) {
        return $this->db->find('orders', $filters);
    }
    
    public function updateStatus($orderId, $status) {
        try {
            // Convertir string a ObjectId si es necesario
            if (is_string($orderId)) {
                $orderId = new MongoDB\BSON\ObjectId($orderId);
            }
            
            return $this->db->update('orders', 
                ['_id' => $orderId],
                ['$set' => ['status' => $status, 'updated_at' => new MongoDB\BSON\UTCDateTime()]]
            );
        } catch (Exception $e) {
            error_log('Error updating order status: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getByStatus($status) {
        return $this->db->find('orders', ['status' => $status]);
    }
    
    public function generateOrderNumber() {
        // Usar uniqid() para garantizar unicidad y compatibilidad con CartController
        return strtoupper('ORD-' . uniqid());
    }
}
?>