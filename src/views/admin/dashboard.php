<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - Cafetería Aroma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>
    
    <div class="dashboard-header">
        <div class="container">
            <h1><i class="bi bi-graph-up"></i> Dashboard Administrativo</h1>
            <p>Monitoreo del desempeño financiero y operativo de la cafetería</p>
        </div>
    </div>
    
    <div class="container mt-4">
        <!-- Métricas Principales -->
        <div class="row">
            <div class="col-md-3">
                <div class="metric-card">
                    <i class="bi bi-cash-coin metric-icon"></i>
                    <h5>Ingresos Totales</h5>
                    <div class="metric-value">$<?php echo number_format($metrics['total_sales'] ?? 0, 2); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <i class="bi bi-bag-check metric-icon"></i>
                    <h5>Pedidos Completados</h5>
                    <div class="metric-value"><?php echo $metrics['completed_orders'] ?? 0; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <i class="bi bi-clock-history metric-icon"></i>
                    <h5>Pedidos Pendientes</h5>
                    <div class="metric-value"><?php echo $metrics['pending_orders'] ?? 0; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <i class="bi bi-graph-up metric-icon"></i>
                    <h5>Valor Promedio</h5>
                    <div class="metric-value">$<?php echo number_format($metrics['average_order_value'] ?? 0, 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Resumen General -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="summary-box">
                    <h3 class="section-title">Resumen General</h3>
                    <div class="summary-item">
                        <strong>Total de Pedidos:</strong>
                        <span><?php echo $metrics['total_orders'] ?? 0; ?></span>
                    </div>
                    <div class="summary-item">
                        <strong>Tasa de Conversión:</strong>
                        <span>
                            <?php 
                            $conversion = ($metrics['total_orders'] ?? 0) > 0 
                                ? round(($metrics['completed_orders'] ?? 0) / ($metrics['total_orders'] ?? 1) * 100, 2)
                                : 0;
                            echo $conversion . '%';
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="row my-4">
            <div class="col-md-12">
                <h3 class="section-title"><i class="bi bi-star"></i> Productos Más Vendidos</h3>
                <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <?php if (!empty($metrics['top_products'])): ?>
                        <?php $rank = 1; ?>
                        <?php foreach ($metrics['top_products'] as $product): ?>
                            <div class="product-item">
                                <div>
                                    <span class="product-rank"><?php echo $rank; ?></span>
                                    <span class="product-name"><?php echo htmlspecialchars($product['product_name'] ?? 'N/A'); ?></span>
                                </div>
                                <span class="product-quantity"><?php echo $product['quantity'] ?? 0; ?> vendidos</span>
                            </div>
                            <?php $rank++; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No hay datos de productos disponibles</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
