<?php
/**
 * Script para generar datos de pedidos de prueba (2025)
 * Distribuye pedidos en distintos rangos de fecha para probar filtros de reportes.
 * Uso (dentro del contenedor):
 *   php /var/www/html/scripts/seed-demo-orders.php
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

echo "Generando datos adicionales para el año 2025...\n\n";

// Últimos 7 días
$rangeConfig = [
    ['label' => 'Últimos 7 días', 'cantidad' => 25, 'min' => 0, 'max' => 6, 'entregaPct' => 85],
    ['label' => 'Últimos 30 días', 'cantidad' => 50, 'min' => 7, 'max' => 30, 'entregaPct' => 80],
    ['label' => 'Últimos 90 días', 'cantidad' => 80, 'min' => 30, 'max' => 90, 'entregaPct' => 75],
];

foreach ($rangeConfig as $config) {
    echo $config['label'] . ': Generando ' . $config['cantidad'] . " pedidos...\n";
    for ($i = 0; $i < $config['cantidad']; $i++) {
        $daysAgo = rand($config['min'], $config['max']);
        $hora = rand(8, 20);
        $minuto = rand(0, 59);
        $fechaUnix = strtotime("-$daysAgo days $hora:$minuto:00");
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

        $status = rand(1, 100) <= $config['entregaPct'] ? 'delivered' : $estados[array_rand($estados)];
        $pedido = [
            'user_id' => 'seed_' . $config['min'] . '_' . rand(1, 60),
            'customer_name' => 'Cliente ' . $config['label'] . ' ' . rand(1, 60),
            'customer_email' => 'seed_' . $config['min'] . '_' . rand(1, 60) . '@example.com',
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
echo "Total de pedidos adicionales generados: $pedidosGenerados\n";

echo "\nSugerencias de prueba:\n";
echo "1. Ir a /admin/reports\n";
echo "2. Probar filtros: Semana, Mes, Trimestre\n";
echo "3. Validar métricas y consolidación de estados\n";
