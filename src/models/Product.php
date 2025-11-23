<?php
require_once __DIR__ . '/../core/Database.php';

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        return $this->db->find('products', ['active' => true]);
    }
    
    public function findById($id) {
        return $this->db->findOne('products', ['_id' => new MongoDB\BSON\ObjectId($id)]);
    }
    
    public function create($data) {
        $data['active'] = true;
        $data['created_at'] = new MongoDB\BSON\UTCDateTime();
        return $this->db->insert('products', $data);
    }

    public function update($id, $data) {
        try {
            $objectId = new MongoDB\BSON\ObjectId($id);
        } catch (Exception $e) {
            return false;
        }

        // Remove immutable fields if present
        unset($data['_id']);
        unset($data['created_at']);

        return $this->db->update('products', ['_id' => $objectId], $data);
    }

    public function delete($id) {
        try {
            $objectId = new MongoDB\BSON\ObjectId($id);
        } catch (Exception $e) {
            return false;
        }

        return $this->db->delete('products', ['_id' => $objectId]);
    }
    
    // Obtener producto por ID (compatible con string)
    public function getProductById($id) {
        // Si es string, convertir a ObjectId
        if (is_string($id)) {
            try {
                $id = new MongoDB\BSON\ObjectId($id);
            } catch (Exception $e) {
                return null;
            }
        }
        
        return $this->db->findOne('products', ['_id' => $id]);
    }
    
    // Obtener todas las categorías válidas del sistema
    public function getCategories() {
        return [
            'cafe-caliente' => 'Cafés y Bebidas Calientes',
            'te' => 'Té e Infusiones Naturales',
            'bebida-fria' => 'Bebidas Frías',
            'pasteleria' => 'Pastelería y Dulces',
            'snack-salado' => 'Snacks y Opciones Saladas'
        ];
    }
}
?>