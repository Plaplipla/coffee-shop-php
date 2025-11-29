<?php
/**
 * Script para generar datos históricos de 2024 para comparaciones año-a-año
 * Distribuye pedidos mensuales del año anterior.
 * Uso (dentro del contenedor):
 *   php /var/www/html/scripts/seed-historical-data.php
 */

define('MONGO_HOST', getenv('MONGO_HOST') ?: 'mongodb');
define('MONGO_PORT', getenv('MONGO_PORT') ?: '27017');
define('MONGO_DB', getenv('MONGO_DB') ?: 'coffee_shop');

define('MONGO_USER', getenv('MONGO_USER') ?: '');
define('MONGO_PASS', getenv('MONGO_PASS') ?: '');

require_once __DIR__ . '/../src/core/Database.php';

$db = Database::getInstance();

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
    ['name' => 'Muffin', 'price' => 2500],
];

$estados = ['pending', 'preparing', 'ready', 'delivered'];
$metodoPago = ['efectivo', 'tarjeta', 'stripe'];
$pedidosGenerados = 0;
$año = 2024;

echo "Generando datos históricos para el año $año...\n\n";

for ($mes = 1; $mes <= 12; $mes++) {
    $pedidosPorMes = rand(20, 40);
    echo "Mes $mes: Generando $pedidosPorMes pedidos...\n";
    
    for ($i = 0; $i < $pedidosPorMes; $i++) {
        $dia = rand(1, 28);
        $hora = rand(8, 20);
        $minuto = rand(0, 59);
        $fechaUnix = strtotime("$año-$mes-$dia $hora:$minuto:00");
        $fecha = new MongoDB\BSON\UTCDateTime($fechaUnix * 1000);
        
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
                'subtotal' => $subtotal,
            ];
            
            $total += $subtotal;
        }
        
        $rand = rand(1, 100);
        $status = $rand <= 80 ? 'delivered' : ($rand <= 90 ? 'ready' : ($rand <= 95 ? 'preparing' : 'pending'));
        
        $pedido = [
            'user_id' => 'hist_user_' . rand(1, 50),
            'customer_name' => 'Cliente Histórico ' . rand(1, 50),
            'customer_email' => 'hist' . rand(1, 50) . '@example.com',
            'items' => $items,
            'total' => $total,
            'status' => $status,
            'payment_method' => $metodoPago[array_rand($metodoPago)],
            'order_date' => date('Y-m-d H:i:s', $fechaUnix),
            'created_at' => $fecha,
            'updated_at' => $fecha,
        ];
        
        $db->insert('pedidos', $pedido);
        $pedidosGenerados++;
    }
}

echo "\n✓ Proceso completado!\n";
echo "Total de pedidos del año $año generados: $pedidosGenerados\n";

echo "\nSugerencias de uso:\n";
echo "1. Ir a /admin/reports\n";
echo "2. Activar comparaciones con el año anterior\n";
echo "3. Verificar tendencias y crecimiento año-a-año\n";
