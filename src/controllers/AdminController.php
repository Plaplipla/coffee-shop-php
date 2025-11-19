<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';

class AdminController {
    private $orderModel;
    private $productModel;
    
    public function __construct() {
        $this->orderModel = new Order();
        $this->productModel = new Product();
        $this->checkAccess();
    }
    
    /* Verificar que el usuario sea administrador */
    private function checkAccess() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'administrador') {
            $_SESSION['error'] = 'Acceso denegado. Se requiere rol de administrador.';
            header('Location: /home');
            exit;
        }
    }
    
    /* Dashboard principal del administrador */
    public function dashboard() {
        $metrics = $this->getGeneralMetrics();
        
        require __DIR__ . '/../views/admin/dashboard.php';
    }
    
    /* Página de reportes detallados */
    public function reports() {
        $period = $_GET['period'] ?? 'month';
        $startDate = $this->getStartDate($period);
        
        // Obtener reportes detallados
        $reports = $this->generateDetailedReports($startDate);
        
        require __DIR__ . '/../views/admin/reports.php';
    }
    
    /* Exportar reportes en PDF o Excel */
    public function export() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            header('Location: /admin/reports');
            exit;
        }
        
        $format = $_POST['format'] ?? 'pdf';
        $period = $_POST['period'] ?? 'month';
        $startDate = $this->getStartDate($period);
        
        $reports = $this->generateDetailedReports($startDate);
        
        if ($format === 'excel') {
            $this->exportToExcel($reports, $period);
        } elseif ($format === 'pdf') {
            $this->exportToPDF($reports, $period);
        } else {
            $_SESSION['error'] = 'Formato no soportado';
            header('Location: /admin/reports');
        }
    }
    
    /* Obtener métricas generales para el dashboard */
    private function getGeneralMetrics() {
        $allOrders = $this->orderModel->getAll();
        
        $totalSales = 0;
        $completedOrders = 0;
        $pendingOrders = 0;
        $itemsSold = [];
        
        foreach ($allOrders as $order) {
            $order = $this->convertToObject($order);
            
            $totalSales += $order->total ?? 0;
            
            if ($order->status === 'delivered') {
                $completedOrders++;
            } elseif ($order->status === 'pending') {
                $pendingOrders++;
            }
            
            // Contar items vendidos
            if (isset($order->items)) {
                $items = is_array($order->items) ? $order->items : (array)$order->items;
                foreach ($items as $item) {
                    $itemArray = is_object($item) ? (array)$item : (is_array($item) ? $item : []);
                    $itemName = $itemArray['name'] ?? 'Producto desconocido';
                    
                    if (!isset($itemsSold[$itemName])) {
                        $itemsSold[$itemName] = 0;
                    }
                    $itemsSold[$itemName] += intval($itemArray['quantity'] ?? 1);
                }
            }
        }
        
        // Ordenar items por cantidad vendida
        arsort($itemsSold);
        $topProducts = array_slice($itemsSold, 0, 5);
        
        return [
            'total_sales' => $totalSales,
            'completed_orders' => $completedOrders,
            'pending_orders' => $pendingOrders,
            'total_orders' => count($allOrders),
            'top_products' => $topProducts,
            'average_order_value' => count($allOrders) > 0 ? round($totalSales / count($allOrders), 2) : 0
        ];
    }
    
    
    // Generar reportes detallados por período
    
    private function generateDetailedReports($startDate) {
        $allOrders = $this->orderModel->getAll();
        
        $totalRevenue = 0;
        $ordersByStatus = [
            'pending' => 0,
            'preparing' => 0,
            'ready' => 0,
            'delivered' => 0
        ];
        $monthlyRevenue = [];
        $consumptionMetrics = [];
        
        foreach ($allOrders as $order) {
            $order = $this->convertToObject($order);
            $orderDate = strtotime($order->order_date ?? date('Y-m-d'));
            
            if ($orderDate >= $startDate) {
                $totalRevenue += $order->total ?? 0;
                
                // Contar por estado
                if (isset($ordersByStatus[$order->status])) {
                    $ordersByStatus[$order->status]++;
                }
                
                // Agrupar por mes
                $monthKey = date('Y-m', $orderDate);
                if (!isset($monthlyRevenue[$monthKey])) {
                    $monthlyRevenue[$monthKey] = 0;
                }
                $monthlyRevenue[$monthKey] += $order->total ?? 0;
                
                // Calcular consumo promedio
                $itemCount = 0;
                if (isset($order->items)) {
                    $items = is_array($order->items) ? $order->items : (array)$order->items;
                    foreach ($items as $item) {
                        $itemArray = is_object($item) ? (array)$item : (is_array($item) ? $item : []);
                        $itemCount += intval($itemArray['quantity'] ?? 1);
                    }
                }
                
                if ($itemCount > 0) {
                    $consumptionMetrics[] = $itemCount;
                }
            }
        }
        
        $avgConsumption = count($consumptionMetrics) > 0 ? 
            round(array_sum($consumptionMetrics) / count($consumptionMetrics), 2) : 0;
        
        return [
            'total_revenue' => $totalRevenue,
            'orders_by_status' => $ordersByStatus,
            'monthly_revenue' => $monthlyRevenue,
            'average_consumption' => $avgConsumption,
            'total_orders' => array_sum($ordersByStatus)
        ];
    }
    
    // Obtener fecha de inicio según período
    private function getStartDate($period) {
        $now = time();
        
        switch ($period) {
            case 'week':
                return $now - (7 * 24 * 60 * 60);
            case 'month':
                return $now - (30 * 24 * 60 * 60);
            case 'quarter':
                return $now - (90 * 24 * 60 * 60);
            case 'year':
                return $now - (365 * 24 * 60 * 60);
            default:
                return $now - (30 * 24 * 60 * 60);
        }
    }
    
    // Exportar a Excel (CSV format)
    private function exportToExcel($reports, $period) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reportes_' . $period . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));  // BOM para Excel con caracteres UTF-8
        
        fputcsv($output, ['REPORTE FINANCIERO - ' . date('Y-m-d')]);
        fputcsv($output, []);
        
        fputcsv($output, ['RESUMEN GENERAL']);
        fputcsv($output, ['Ingresos Totales', '$' . number_format($reports['total_revenue'], 2)]);
        fputcsv($output, ['Total de Pedidos', $reports['total_orders']]);
        fputcsv($output, ['Consumo Promedio', $reports['average_consumption'] . ' items']);
        fputcsv($output, []);
        
        fputcsv($output, ['PEDIDOS POR ESTADO']);
        fputcsv($output, ['Estado', 'Cantidad']);
        foreach ($reports['orders_by_status'] as $status => $count) {
            fputcsv($output, [$this->getStatusLabel($status), $count]);
        }
        fputcsv($output, []);
        
        fputcsv($output, ['INGRESOS MENSUALES']);
        fputcsv($output, ['Mes', 'Ingresos']);
        foreach ($reports['monthly_revenue'] as $month => $revenue) {
            fputcsv($output, [$month, '$' . number_format($revenue, 2)]);
        }
        
        fclose($output);
        exit;
    }
    
    // Exportar a PDF (versión HTML imprimible)
    private function exportToPDF($reports, $period) {
        $html = $this->generatePDFHTML($reports, $period);
        
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="reportes_' . $period . '_' . date('Y-m-d') . '.html"');
        
        echo $html;
        exit;
    }
    
    // Generar HTML para PDF 
    private function generatePDFHTML($reports, $period) {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte Financiero</title>
    <style>
        * { margin: 0; padding: 0; }
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            color: #333;
            background: white;
        }
        .header { 
            border-bottom: 3px solid #8B4513; 
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        h1 { color: #8B4513; font-size: 28px; }
        h2 { color: #8B4513; font-size: 18px; margin: 20px 0 10px 0; }
        p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f5deb3; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .summary { 
            background: #f0f0f0; 
            padding: 15px; 
            border-radius: 5px;
            margin: 15px 0;
        }
        .summary-item { 
            margin: 8px 0; 
            font-size: 14px;
        }
        .amount { 
            text-align: right; 
            font-weight: bold;
            color: #8B4513;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 12px;
            color: #999;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte Financiero - Cafetería Aroma</h1>
        <p><strong>Fecha:</strong> ' . date('d/m/Y H:i:s') . '</p>
        <p><strong>Período:</strong> ' . ucfirst($period) . '</p>
    </div>
    
    <div class="summary">
        <h2>Resumen General</h2>
        <div class="summary-item"><strong>Ingresos Totales:</strong> <span class="amount">$' . number_format($reports['total_revenue'], 2) . '</span></div>
        <div class="summary-item"><strong>Total de Pedidos:</strong> ' . $reports['total_orders'] . '</div>
        <div class="summary-item"><strong>Consumo Promedio por Pedido:</strong> ' . $reports['average_consumption'] . ' items</div>
    </div>
    
    <h2>Pedidos por Estado</h2>
    <table>
        <tr>
            <th>Estado</th>
            <th>Cantidad</th>
        </tr>';
        
        foreach ($reports['orders_by_status'] as $status => $count) {
            $statusLabel = $this->getStatusLabel($status);
            $html .= '<tr><td>' . $statusLabel . '</td><td>' . $count . '</td></tr>';
        }
        
        $html .= '</table>
    
    <h2>Ingresos Mensuales</h2>
    <table>
        <tr>
            <th>Mes</th>
            <th>Ingresos</th>
        </tr>';
        
        foreach ($reports['monthly_revenue'] as $month => $revenue) {
            $html .= '<tr><td>' . $month . '</td><td class="amount">$' . number_format($revenue, 2) . '</td></tr>';
        }
        
        $html .= '</table>
    
    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de Cafetería Aroma.</p>
        <p>Para más información, contacte al administrador.</p>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    private function convertToObject($data) {
        return is_array($data) ? (object)$data : $data;
    }
    
    private function getStatusLabel($status) {
        $labels = [
            'pending' => 'Pendiente',
            'preparing' => 'En Preparación',
            'ready' => 'Listo',
            'delivered' => 'Entregado'
        ];
        return $labels[$status] ?? ucfirst($status);
    }
    
    private function calculateTotalRevenue() {
        $orders = $this->orderModel->getAll();
        $revenue = 0;
        
        foreach ($orders as $order) {
            $order = $this->convertToObject($order);
            if ($order->status === 'delivered') {
                $revenue += $order->total ?? 0;
            }
        }
        
        return $revenue;
    }
}
?>
