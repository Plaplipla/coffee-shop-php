<?php
/**
 * Script para agregar más datos ficticios al año actual (2025)
 * Genera pedidos distribuidos en diferentes períodos para probar filtros
 */

define('MONGO_HOST', getenv('MONGO_HOST') ?: 'mongodb');
define('MONGO_PORT', getenv('MONGO_PORT') ?: '27017');
define('MONGO_DB', getenv('MONGO_DB') ?: 'coffee_shop');
define('MONGO_USER', getenv('MONGO_USER') ?: '');
define('MONGO_PASS', getenv('MONGO_PASS') ?: '');

require_once '/var/www/html/core/Database.php';

$db = Database::getInstance();

// Productos disponibles
$productos = [
    ['name' => 'Café Americano', 'price' => 2500],
    ['name' => 'Café Latte', 'price' => 3500],
    ['name' => 'Cappuccino', 'price' => 3800],
    ['name' => 'Espresso', 'price' => 2000],
    ['name' => 'Mocaccino', 'price' => 4000],
    ['name' => 'Café Frappé', 'price' => 4500],
    ['name' => 'Croissant', 'price' => 2800],
    ['name' => 'Brownie', 'price' => 3200],
    ['name' => 'Cheesecake', 'price' => 4200],
    ['name' => 'Muffin', 'price' => 2500]
];

$estados = ['pending', 'preparing', 'ready', 'delivered'];
$metodoPago = ['efectivo', 'tarjeta', 'stripe'];

$pedidosGenerados = 0;

echo "Generando datos adicionales para el año 2025...\n\n";

// Generar pedidos para la última semana (últimos 7 días)
echo "Últimos 7 días: Generando 25 pedidos...\n";
for ($i = 0; $i < 25; $i++) {
    $daysAgo = rand(0, 6);
    $hora = rand(8, 20);
    $minuto = rand(0, 59);
    
    $fecha = new MongoDB\BSON\UTCDateTime(
        strtotime("-$daysAgo days $hora:$minuto:00") * 1000
    );
    
    $numItems = rand(1, 4);
    $items = [];
    $total = 0;
    
    for ($j = 0; $j < $numItems; $j++) {
        $producto = $productos[array_rand($productos)];
        $cantidad = rand(1, 3);
        $subtotal = $producto['price'] * $cantidad;
        
        $items[] = [
            'product_id' => 'prod_' . uniqid(),
            'name' => $producto['name'],
            'price' => $producto['price'],
            'quantity' => $cantidad,
            'subtotal' => $subtotal
        ];
        
        $total += $subtotal;
    }
    
    $status = rand(1, 100) <= 85 ? 'delivered' : $estados[array_rand($estados)];
    
    $pedido = [
        'user_id' => 'user_week_' . rand(1, 20),
        'customer_name' => 'Cliente Semana ' . rand(1, 20),
        'customer_email' => 'week' . rand(1, 20) . '@example.com',
        'items' => $items,
        'total' => $total,
        'status' => $status,
        'payment_method' => $metodoPago[array_rand($metodoPago)],
        'order_date' => date('Y-m-d H:i:s', strtotime("-$daysAgo days $hora:$minuto:00")),
        'created_at' => $fecha,
        'updated_at' => $fecha
    ];
    
    $db->insert('pedidos', $pedido);
    $pedidosGenerados++;
}

// Generar pedidos para los últimos 30 días (mes actual - noviembre)
echo "Últimos 30 días: Generando 50 pedidos...\n";
for ($i = 0; $i < 50; $i++) {
    $daysAgo = rand(7, 30);
    $hora = rand(8, 20);
    $minuto = rand(0, 59);
    
    $fecha = new MongoDB\BSON\UTCDateTime(
        strtotime("-$daysAgo days $hora:$minuto:00") * 1000
    );
    
    $numItems = rand(1, 4);
    $items = [];
    $total = 0;
    
    for ($j = 0; $j < $numItems; $j++) {
        $producto = $productos[array_rand($productos)];
        $cantidad = rand(1, 3);
        $subtotal = $producto['price'] * $cantidad;
        
        $items[] = [
            'product_id' => 'prod_' . uniqid(),
            'name' => $producto['name'],
            'price' => $producto['price'],
            'quantity' => $cantidad,
            'subtotal' => $subtotal
        ];
        
        $total += $subtotal;
    }
    
    $status = rand(1, 100) <= 80 ? 'delivered' : $estados[array_rand($estados)];
    
    $pedido = [
        'user_id' => 'user_month_' . rand(1, 40),
        'customer_name' => 'Cliente Mes ' . rand(1, 40),
        'customer_email' => 'month' . rand(1, 40) . '@example.com',
        'items' => $items,
        'total' => $total,
        'status' => $status,
        'payment_method' => $metodoPago[array_rand($metodoPago)],
        'order_date' => date('Y-m-d H:i:s', strtotime("-$daysAgo days $hora:$minuto:00")),
        'created_at' => $fecha,
        'updated_at' => $fecha
    ];
    
    $db->insert('pedidos', $pedido);
    $pedidosGenerados++;
}

// Generar pedidos para el trimestre (últimos 90 días)
echo "Últimos 90 días: Generando 80 pedidos...\n";
for ($i = 0; $i < 80; $i++) {
    $daysAgo = rand(30, 90);
    $hora = rand(8, 20);
    $minuto = rand(0, 59);
    
    $fecha = new MongoDB\BSON\UTCDateTime(
        strtotime("-$daysAgo days $hora:$minuto:00") * 1000
    );
    
    $numItems = rand(1, 4);
    $items = [];
    $total = 0;
    
    for ($j = 0; $j < $numItems; $j++) {
        $producto = $productos[array_rand($productos)];
        $cantidad = rand(1, 3);
        $subtotal = $producto['price'] * $cantidad;
        
        $items[] = [
            'product_id' => 'prod_' . uniqid(),
            'name' => $producto['name'],
            'price' => $producto['price'],
            'quantity' => $cantidad,
            'subtotal' => $subtotal
        ];
        
        $total += $subtotal;
    }
    
    $status = rand(1, 100) <= 75 ? 'delivered' : $estados[array_rand($estados)];
    
    $pedido = [
        'user_id' => 'user_quarter_' . rand(1, 60),
        'customer_name' => 'Cliente Trimestre ' . rand(1, 60),
        'customer_email' => 'quarter' . rand(1, 60) . '@example.com',
        'items' => $items,
        'total' => $total,
        'status' => $status,
        'payment_method' => $metodoPago[array_rand($metodoPago)],
        'order_date' => date('Y-m-d H:i:s', strtotime("-$daysAgo days $hora:$minuto:00")),
        'created_at' => $fecha,
        'updated_at' => $fecha
    ];
    
    $db->insert('pedidos', $pedido);
    $pedidosGenerados++;
}

echo "\n✓ Proceso completado!\n";
echo "Total de pedidos adicionales generados: $pedidosGenerados\n";
echo "\nAhora puedes:\n";
echo "1. Ir a /admin/reports\n";
echo "2. Probar los filtros:\n";
echo "   - 'Última Semana' debería mostrar ~25 pedidos\n";
echo "   - 'Último Mes' debería mostrar ~75 pedidos (7 + 50 + 18 del trimestre)\n";
echo "   - 'Último Trimestre' debería mostrar ~155 pedidos (7 + 50 + 80 + 18)\n";
echo "3. Activar comparaciones para ver diferencias entre períodos\n";
?>
