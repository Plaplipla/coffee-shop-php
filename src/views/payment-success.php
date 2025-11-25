<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        </div>
                        
                        <h1 class="display-4 mb-3">¡Pago Exitoso!</h1>
                        <p class="lead text-muted mb-4">
                            Tu pago ha sido procesado correctamente
                        </p>
                        
                        <?php if (isset($_SESSION['order_data'])): ?>
                        <div class="alert alert-success mb-4">
                            <h5><i class="bi bi-receipt"></i> Número de Orden</h5>
                            <h3 class="mb-0"><strong><?php echo htmlspecialchars($_SESSION['order_data']['order_number']); ?></strong></h3>
                        </div>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="bi bi-info-circle"></i> Detalles del Pedido</h5>
                                
                                <div class="row text-start">
                                    <div class="col-md-6">
                                        <p><strong>Cliente:</strong><br>
                                        <?php echo htmlspecialchars($_SESSION['order_data']['customer_name']); ?></p>
                                        
                                        <p><strong>Email:</strong><br>
                                        <?php echo htmlspecialchars($_SESSION['order_data']['customer_email']); ?></p>
                                        
                                        <p><strong>Teléfono:</strong><br>
                                        <?php echo htmlspecialchars($_SESSION['order_data']['customer_phone']); ?></p>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <p><strong>Método de Pago:</strong><br>
                                        <i class="bi bi-credit-card-2-front text-success"></i> Stripe (Pagado)</p>
                                        
                                        <p><strong>Tipo de Entrega:</strong><br>
                                        <?php 
                                        echo $_SESSION['order_data']['delivery_type'] === 'delivery' 
                                            ? '<i class="bi bi-truck"></i> A domicilio' 
                                            : '<i class="bi bi-shop"></i> Retiro en local'; 
                                        ?></p>
                                        
                                        <?php if ($_SESSION['order_data']['delivery_type'] === 'delivery'): ?>
                                        <p><strong>Dirección:</strong><br>
                                        <?php echo htmlspecialchars($_SESSION['order_data']['delivery_address']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <h6 class="mb-3">Productos:</h6>
                                <?php foreach ($_SESSION['order_data']['items'] as $item): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                                    <span>$<?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
                                </div>
                                <?php endforeach; ?>
                                
                                <?php if ($_SESSION['order_data']['delivery_fee'] > 0): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Envío</span>
                                    <span>$<?php echo number_format($_SESSION['order_data']['delivery_fee'], 0, ',', '.'); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($_SESSION['order_data']['discount_amount']) && $_SESSION['order_data']['discount_amount'] > 0): ?>
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Descuento (<?php echo htmlspecialchars($_SESSION['order_data']['discount_code']); ?>)</span>
                                    <span>-$<?php echo number_format($_SESSION['order_data']['discount_amount'], 0, ',', '.'); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between">
                                    <strong class="h5">Total Pagado:</strong>
                                    <strong class="h5 text-success">$<?php echo number_format($_SESSION['order_data']['total'], 0, ',', '.'); ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-envelope"></i> 
                            <strong>Confirmación enviada</strong><br>
                            Hemos enviado un email de confirmación con todos los detalles de tu pedido.
                        </div>
                        
                        <div class="d-grid gap-2 d-md-block">
                            <a href="/home" class="btn btn-primary btn-lg">
                                <i class="bi bi-house"></i> Volver al Inicio
                            </a>
                            <a href="/order-history" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-clock-history"></i> Ver mis Pedidos
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-muted">
                        <i class="bi bi-shield-check"></i> 
                        Pago procesado de forma segura por Stripe
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
