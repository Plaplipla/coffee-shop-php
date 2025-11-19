<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Cafetería Aroma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>
    
    <div class="reports-header">
        <div class="container">
            <h1><i class="bi bi-file-earmark-text"></i> Reportes Financieros</h1>
            <p>Análisis detallado del desempeño de ventas y operaciones</p>
        </div>
    </div>
    
    <div class="container">
        <!-- Selector de Período -->
        <div class="period-selector">
            <a href="/admin/reports?period=week" class="period-btn <?php echo ($period === 'week') ? 'active' : ''; ?>">
                <i class="bi bi-calendar-week"></i> Última Semana
            </a>
            <a href="/admin/reports?period=month" class="period-btn <?php echo ($period === 'month') ? 'active' : ''; ?>">
                <i class="bi bi-calendar-month"></i> Último Mes
            </a>
            <a href="/admin/reports?period=quarter" class="period-btn <?php echo ($period === 'quarter') ? 'active' : ''; ?>">
                <i class="bi bi-calendar3"></i> Último Trimestre
            </a>
            <a href="/admin/reports?period=year" class="period-btn <?php echo ($period === 'year') ? 'active' : ''; ?>">
                <i class="bi bi-calendar-check"></i> Último Año
            </a>
        </div>

        <!-- Resumen de Reportes -->
        <h2 class="section-title">Resumen de Ingresos</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="report-card">
                    <h5><i class="bi bi-cash-coin"></i> Ingresos Totales</h5>
                    <div class="report-value">$<?php echo number_format($reports['total_revenue'], 2); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="report-card">
                    <h5><i class="bi bi-bag-check"></i> Total de Pedidos</h5>
                    <div class="report-value"><?php echo $reports['total_orders']; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="report-card">
                    <h5><i class="bi bi-graph-up"></i> Consumo Promedio</h5>
                    <div class="report-value"><?php echo $reports['average_consumption']; ?> items</div>
                </div>
            </div>
        </div>

        <!-- Pedidos por Estado -->
        <h2 class="section-title">Desglose de Pedidos por Estado</h2>
        <div class="report-card">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports['orders_by_status'] as $status => $count): ?>
                        <?php 
                            $percentage = $reports['total_orders'] > 0 
                                ? round(($count / $reports['total_orders']) * 100, 1)
                                : 0;
                            $statusLabel = [
                                'pending' => 'Pendiente',
                                'preparing' => 'En Preparación',
                                'ready' => 'Listo',
                                'delivered' => 'Entregado'
                            ][$status] ?? ucfirst($status);
                            $statusClass = 'status-' . $status;
                        ?>
                        <tr>
                            <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                            <td><?php echo $count; ?></td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" style="width: <?php echo $percentage; ?>%; background: #8B4513;">
                                        <?php echo $percentage; ?>%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Ingresos Mensuales -->
        <h2 class="section-title">Ingresos Mensuales</h2>
        <div class="report-card">
            <?php if (!empty($reports['monthly_revenue'])): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Ingresos</th>
                            <th>Gráfico</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $maxRevenue = max($reports['monthly_revenue']);
                            foreach ($reports['monthly_revenue'] as $month => $revenue): 
                        ?>
                            <tr>
                                <td><strong><?php echo $month; ?></strong></td>
                                <td><strong>$<?php echo number_format($revenue, 2); ?></strong></td>
                                <td>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar" style="width: <?php echo ($maxRevenue > 0) ? ($revenue / $maxRevenue * 100) : 0; ?>%; background: #d4af37; color: #8B4513; font-weight: bold;">
                                            <?php echo round(($maxRevenue > 0) ? ($revenue / $maxRevenue * 100) : 0, 0); ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No hay datos disponibles para este período</p>
            <?php endif; ?>
        </div>

        <!-- Exportar Reportes -->
        <div class="export-section">
            <h3 style="color: #8B4513; margin-bottom: 20px;">
                <i class="bi bi-download"></i> Exportar Reportes
            </h3>
            <form method="POST" action="/admin/export" class="export-form">
                <select name="format" class="form-select" style="max-width: 200px;">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel (CSV)</option>
                </select>
                <input type="hidden" name="period" value="<?php echo htmlspecialchars($period); ?>">
                <button type="submit" class="btn-export">
                    <i class="bi bi-file-earmark-pdf"></i> Descargar
                </button>
            </form>
            <small class="text-muted" style="display: block; margin-top: 10px;">
                El archivo se descargará en el formato seleccionado con todos los datos del período actual.
            </small>
        </div>

        <!-- Volver al Dashboard -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="/admin/dashboard" class="btn" style="background: #8B4513; color: white;">
                <i class="bi bi-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
