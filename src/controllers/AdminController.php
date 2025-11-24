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
        $contactMessages = $this->getRecentContactMessages();
        
        require __DIR__ . '/../views/admin/dashboard.php';
    }
    
    /* Obtener mensajes de contacto recientes */
    private function getRecentContactMessages($limit = 10) {
        $db = Database::getInstance();
        $messages = $db->find('contactos', [], ['sort' => ['fecha' => -1], 'limit' => $limit]);
        return $messages;
    }
    
    /* Ver todos los mensajes de contacto */
    public function messages() {
        $db = Database::getInstance();
        $allMessages = $db->find('contactos', [], ['sort' => ['fecha' => -1]]);
        
        require __DIR__ . '/../views/admin/messages.php';
    }
    
    /* Marcar mensaje como leído */
    public function markMessageRead() {
        if (!isset($_GET['id'])) {
            header('Location: /admin/messages');
            exit;
        }
        
        $db = Database::getInstance();
        $db->update('contactos', 
            ['_id' => new MongoDB\BSON\ObjectId($_GET['id'])], 
            ['leido' => true]
        );
        
        $_SESSION['success'] = 'Mensaje marcado como leído';
        header('Location: /admin/messages');
        exit;
    }
    
    /* Página de reportes detallados */
    public function reports() {
        $period = $_GET['period'] ?? 'month';
        $comparison = $_GET['comparison'] ?? 'none'; // none, previous, same
        
        $startDate = $this->getStartDate($period);
        
        // Obtener reportes detallados del período actual
        $reports = $this->generateDetailedReports($startDate);
        
        // Generar datos de comparación
        $comparisonData = null;
        if ($comparison !== 'none') {
            $comparisonData = $this->generateComparisonData($period, $comparison, $reports);
        }
        
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
        $topProductsArray = array_slice($itemsSold, 0, 5, true);
        
        // Convertir a formato esperado por la vista
        $topProducts = [];
        foreach ($topProductsArray as $productName => $quantity) {
            $topProducts[] = [
                'product_name' => $productName,
                'quantity' => $quantity
            ];
        }
        
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
            
            // Intentar obtener la fecha desde order_date o created_at
            $orderDate = null;
            if (isset($order->order_date) && !empty($order->order_date)) {
                $orderDate = strtotime($order->order_date);
            } elseif (isset($order->created_at)) {
                if ($order->created_at instanceof MongoDB\BSON\UTCDateTime) {
                    $orderDate = $order->created_at->toDateTime()->getTimestamp();
                } else {
                    $orderDate = strtotime((string)$order->created_at);
                }
            } else {
                $orderDate = time();
            }
            
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
    
    // Generar datos de comparación ficticios
    private function generateComparisonData($period, $type, $currentData) {
        // Calcular variación base según el tipo de comparación
        if ($type === 'previous') {
            // Período anterior: generar datos con variación del -15% al +25%
            $revenueVariation = rand(85, 125) / 100;
            $ordersVariation = rand(80, 130) / 100;
        } else {
            // Mismo período año anterior: generar datos con variación del -20% al +40%
            $revenueVariation = rand(80, 140) / 100;
            $ordersVariation = rand(75, 135) / 100;
        }
        
        // Generar datos históricos ficticios basados en los actuales
        $historicalRevenue = round($currentData['total_revenue'] * $revenueVariation, 2);
        $historicalOrders = round($currentData['total_orders'] * $ordersVariation);
        
        // Generar órdenes por estado con variaciones
        $historicalOrdersByStatus = [];
        foreach ($currentData['orders_by_status'] as $status => $count) {
            $statusVariation = rand(70, 140) / 100;
            $historicalOrdersByStatus[$status] = round($count * $statusVariation);
        }
        
        // Generar ingresos mensuales históricos
        $historicalMonthlyRevenue = [];
        foreach ($currentData['monthly_revenue'] as $month => $revenue) {
            $monthVariation = rand(75, 135) / 100;
            $historicalMonthlyRevenue[$month] = round($revenue * $monthVariation, 2);
        }
        
        // Calcular consumo promedio histórico
        $consumptionVariation = rand(85, 115) / 100;
        $historicalConsumption = round($currentData['average_consumption'] * $consumptionVariation, 2);
        
        // Calcular cambios porcentuales
        $revenueChange = $currentData['total_revenue'] > 0 ? 
            round((($currentData['total_revenue'] - $historicalRevenue) / $historicalRevenue) * 100, 1) : 0;
        
        $ordersChange = $historicalOrders > 0 ? 
            round((($currentData['total_orders'] - $historicalOrders) / $historicalOrders) * 100, 1) : 0;
        
        $consumptionChange = $historicalConsumption > 0 ? 
            round((($currentData['average_consumption'] - $historicalConsumption) / $historicalConsumption) * 100, 1) : 0;
        
        return [
            'type' => $type,
            'period_name' => $type === 'previous' ? 'Período Anterior' : 'Mismo Período Año Anterior',
            'total_revenue' => $historicalRevenue,
            'total_orders' => $historicalOrders,
            'orders_by_status' => $historicalOrdersByStatus,
            'monthly_revenue' => $historicalMonthlyRevenue,
            'average_consumption' => $historicalConsumption,
            'revenue_change' => $revenueChange,
            'orders_change' => $ordersChange,
            'consumption_change' => $consumptionChange
        ];
    }
    
    // Exportar a Excel con estilos (HTML format compatible con Excel)
    private function exportToExcel($reports, $period) {
        $filename = 'reportes_' . $period . '_' . date('Y-m-d') . '.xls';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        
        // Calcular número de filas
        $totalRows = 10 + count($reports['orders_by_status']) + count($reports['monthly_revenue']);
        
        $html = '<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" 
      xmlns:x="urn:schemas-microsoft-com:office:excel" 
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Reporte</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                        <x:Selected/>
                        <x:ProtectContents>False</x:ProtectContents>
                        <x:ProtectObjects>False</x:ProtectObjects>
                        <x:ProtectScenarios>False</x:ProtectScenarios>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <style>
        table { mso-displayed-decimal-separator:"\."; mso-displayed-thousand-separator:"\,"; }
        @page { margin:.75in .7in .75in .7in; mso-header-margin:.3in; mso-footer-margin:.3in; }
    </style>
</head>
<body>
    <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse; table-layout: fixed; width: 500px;">
        <col width="350">
        <col width="150">
        <tr>
            <td colspan="2" style="background-color: #8B4513; color: white; font-weight: bold; font-size: 16px; padding: 12px; text-align: center; border: 2px solid #654321;">
                REPORTE FINANCIERO - CAFETERÍA AROMA
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color: #A0522D; color: white; font-size: 11px; padding: 6px; text-align: center;">
                Fecha: ' . date('d/m/Y H:i') . ' | Período: ' . ucfirst($period) . '
            </td>
        </tr>
        <tr>
            <td colspan="2" style="height: 5px; border: none;"></td>
        </tr>
        
        <tr>
            <td colspan="2" style="background-color: #D2691E; color: white; font-weight: bold; font-size: 13px; padding: 8px; border: 2px solid #CD853F;">
                RESUMEN GENERAL
            </td>
        </tr>
        <tr style="background-color: #FFF8DC;">
            <td style="padding: 8px; font-weight: bold; color: #654321; border: 1px solid #ddd; border-left: 3px solid #8B4513;">Ingresos Totales</td>
            <td style="padding: 8px; font-weight: bold; color: #006400; text-align: right; border: 1px solid #ddd;">$' . number_format($reports['total_revenue'], 2) . '</td>
        </tr>
        <tr style="background-color: #FFFAF0;">
            <td style="padding: 8px; font-weight: bold; color: #654321; border: 1px solid #ddd; border-left: 3px solid #8B4513;">Total de Pedidos</td>
            <td style="padding: 8px; font-weight: bold; color: #006400; text-align: right; border: 1px solid #ddd;">' . $reports['total_orders'] . '</td>
        </tr>
        <tr style="background-color: #FFF8DC;">
            <td style="padding: 8px; font-weight: bold; color: #654321; border: 1px solid #ddd; border-left: 3px solid #8B4513;">Consumo Promedio por Pedido</td>
            <td style="padding: 8px; font-weight: bold; color: #006400; text-align: right; border: 1px solid #ddd;">' . $reports['average_consumption'] . ' items</td>
        </tr>
        <tr>
            <td colspan="2" style="height: 5px; border: none;"></td>
        </tr>
        
        <tr>
            <td colspan="2" style="background-color: #D2691E; color: white; font-weight: bold; font-size: 13px; padding: 8px; border: 2px solid #CD853F;">
                PEDIDOS POR ESTADO
            </td>
        </tr>
        <tr style="background-color: #CD853F;">
            <td style="color: white; font-weight: bold; padding: 8px; border: 2px solid #8B6914; text-align: left;">Estado</td>
            <td style="color: white; font-weight: bold; padding: 8px; border: 2px solid #8B6914; text-align: right;">Cantidad</td>
        </tr>';
        
        $rowCount = 0;
        foreach ($reports['orders_by_status'] as $status => $count) {
            $statusLabel = $this->getStatusLabel($status);
            $bgColor = ($rowCount % 2 == 0) ? '#FFFAF0' : '#FFF8DC';
            $html .= '<tr style="background-color: ' . $bgColor . ';">
                <td style="padding: 8px; border: 1px solid #ddd;">' . $statusLabel . '</td>
                <td style="padding: 8px; font-weight: bold; color: #006400; text-align: right; border: 1px solid #ddd;">' . $count . '</td>
            </tr>';
            $rowCount++;
        }
        
        $html .= '<tr>
            <td colspan="2" style="height: 5px; border: none;"></td>
        </tr>
        
        <tr>
            <td colspan="2" style="background-color: #D2691E; color: white; font-weight: bold; font-size: 13px; padding: 8px; border: 2px solid #CD853F;">
                INGRESOS MENSUALES
            </td>
        </tr>
        <tr style="background-color: #CD853F;">
            <td style="color: white; font-weight: bold; padding: 8px; border: 2px solid #8B6914; text-align: left;">Mes</td>
            <td style="color: white; font-weight: bold; padding: 8px; border: 2px solid #8B6914; text-align: right;">Ingresos</td>
        </tr>';
        
        $rowCount = 0;
        foreach ($reports['monthly_revenue'] as $month => $revenue) {
            $bgColor = ($rowCount % 2 == 0) ? '#FFFAF0' : '#FFF8DC';
            $html .= '<tr style="background-color: ' . $bgColor . ';">
                <td style="padding: 8px; border: 1px solid #ddd;">' . $month . '</td>
                <td style="padding: 8px; font-weight: bold; color: #006400; text-align: right; border: 1px solid #ddd;">$' . number_format($revenue, 2) . '</td>
            </tr>';
            $rowCount++;
        }
        
        $html .= '<tr>
            <td colspan="2" style="height: 5px; border: none;"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; color: #666; font-size: 10px; padding: 10px; border-top: 2px solid #D2691E; font-style: italic;">
                Reporte generado por Cafetería Aroma - Sistema v1.0
            </td>
        </tr>
    </table>
</body>
</html>';
        
        echo $html;
        exit;
    }
    
    // Exportar a PDF (usando DomPDF)
    private function exportToPDF($reports, $period) {
        // Cargar autoloader de Composer si existe
        $autoloadPath = __DIR__ . '/../vendor/autoload.php';
        if (file_exists($autoloadPath)) {
            require_once $autoloadPath;
        }
        
        // Verificar si DomPDF está disponible
        if (!class_exists('Dompdf\Dompdf')) {
            // Fallback: exportar como HTML si DomPDF no está instalado
            $html = $this->generatePDFHTML($reports, $period);
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: attachment; filename="reportes_' . $period . '_' . date('Y-m-d') . '.html"');
            echo $html;
            exit;
        }
        
        // Generar HTML
        $html = $this->generatePDFHTML($reports, $period);
        
        // Crear instancia de DomPDF
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Enviar el PDF al navegador
        $filename = 'reportes_' . $period . '_' . date('Y-m-d') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => 1]);
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
