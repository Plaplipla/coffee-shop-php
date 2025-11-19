<?php
require_once __DIR__ . '/../core/Database.php';

class Cart {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }

    //Estos métodos se usarán solo cuando el usuario esté logueado
    public function migrateSessionToUser($sessionCart, $userId) {
        // Migrar carrito de sesión a base de datos cuando el usuario haga login
        foreach ($sessionCart as $item) {
            $this->addToCart($userId, $item['product_id'], $item['quantity']);
        }
        return true;
    }
    
    // Solo para usuarios logueados
    public function addToCart($userId, $productId, $quantity = 1) {
        // Verificar si el producto ya está en el carrito
        $existingItem = $this->db->findOne('cart', [
            'user_id' => $userId,
            'product_id' => $productId
        ]);
        
        if ($existingItem) {
            // Actualizar cantidad
            return $this->db->update('cart', 
                ['_id' => $existingItem['_id']],
                ['$set' => ['quantity' => $existingItem['quantity'] + $quantity]]
            );
        } else {
            // Agregar nuevo item
            return $this->db->insert('cart', [
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);
        }
    }
    
    public function removeFromCart($userId, $productId) {
        return $this->db->delete('cart', [
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }
    
    public function updateQuantity($userId, $productId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($userId, $productId);
        }
        
        return $this->db->update('cart', 
            ['user_id' => $userId, 'product_id' => $productId],
            ['$set' => ['quantity' => $quantity]]
        );
    }
    
    public function getCartItems($userId) {
        $pipeline = [
            ['$match' => ['user_id' => $userId]],
            [
                '$lookup' => [
                    'from' => 'products',
                    'localField' => 'product_id',
                    'foreignField' => '_id',
                    'as' => 'product_info'
                ]
            ],
            ['$unwind' => '$product_info'],
            [
                '$project' => [
                    'product_id' => 1,
                    'quantity' => 1,
                    'name' => '$product_info.name',
                    'price' => '$product_info.price',
                    'image' => '$product_info.image',
                    'description' => '$product_info.description',
                    'icon' => '$product_info.icon'
                ]
            ]
        ];
        
        return $this->db->aggregate('cart', $pipeline);
    }
    
    public function getCartTotal($userId) {
        $items = $this->getCartItems($userId);
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }
    
    public function getCartItemCount($userId) {
        $result = $this->db->aggregate('cart', [
            ['$group' => ['_id' => null, 'total' => ['$sum' => '$quantity']]]
        ]);
        
        return empty($result) ? 0 : $result[0]['total'];
    }
    
    public function clearCart($userId) {
        return $this->db->deleteMany('cart', ['user_id' => $userId]);
    }
}
?>