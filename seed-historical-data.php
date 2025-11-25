<?php
/**
 * Script para poblar la base de datos con datos históricos ficticios
 * Genera pedidos del año 2024 para permitir comparaciones en reportes
 */

// Definir constantes de conexión
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

// Generar pedidos para cada mes de 2024
$pedidosGenerados = 0;
$año = 2024;

echo "Generando datos históricos para el año $año...\n\n";

for ($mes = 1; $mes <= 12; $mes++) {
    // Número de pedidos por mes (varía entre 20-40 pedidos)
    $pedidosPorMes = rand(20, 40);
    
    echo "Mes $mes: Generando $pedidosPorMes pedidos...\n";
    
    for ($i = 0; $i < $pedidosPorMes; $i++) {
        // Fecha aleatoria dentro del mes
        $dia = rand(1, 28); // Usar 28 para evitar problemas con febrero
        $hora = rand(8, 20); // Horario de 8am a 8pm
        $minuto = rand(0, 59);
        
        $fecha = new MongoDB\BSON\UTCDateTime(
            strtotime("$año-$mes-$dia $hora:$minuto:00") * 1000
        );
        
        // Generar 1-4 items por pedido
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
        
        // Estado del pedido (80% entregados, resto variado)
        $rand = rand(1, 100);
        if ($rand <= 80) {
            $status = 'delivered';
        } elseif ($rand <= 90) {
            $status = 'ready';
        } elseif ($rand <= 95) {
            $status = 'preparing';
        } else {
            $status = 'pending';
        }
        
        // Crear pedido
        $pedido = [
            'user_id' => 'hist_user_' . rand(1, 50), // 50 usuarios ficticios
            'customer_name' => 'Cliente Histórico ' . rand(1, 50),
            'customer_email' => 'cliente' . rand(1, 50) . '@example.com',
            'items' => $items,
            'total' => $total,
            'status' => $status,
            'payment_method' => $metodoPago[array_rand($metodoPago)],
            'order_date' => date('Y-m-d H:i:s', strtotime("$año-$mes-$dia $hora:$minuto:00")),
            'created_at' => $fecha,
            'updated_at' => $fecha
        ];
        
        // Insertar en la base de datos
        $db->insert('pedidos', $pedido);
        $pedidosGenerados++;
    }
}

echo "\n✓ Proceso completado!\n";
echo "Total de pedidos generados: $pedidosGenerados\n";
echo "\nAhora puedes usar los reportes con comparaciones del año anterior.\n";
?>
