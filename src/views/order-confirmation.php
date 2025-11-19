<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="alert alert-success text-center" role="alert">
                    <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">¡Pedido Confirmado!</h3>
                    <p class="mb-0">Tu pedido ha sido recibido correctamente. Te contactaremos pronto para confirmar los detalles.</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-coffee text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Resumen del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <?php // Mostrar número de orden si está disponible ?>
                        <?php if (!empty($orderData['order_number'])): ?>
                        <div class="mb-4 text-center">
                            <p class="mb-2"><strong>Número de Pedido</strong></p>
                            <div class="badge bg-secondary w-100" style="font-size: 1.3rem; padding: 0.75rem 1rem; display: inline-block; white-space: nowrap;"><?php echo htmlspecialchars($orderData['order_number']); ?></div>
                        </div>
                        <hr>
                        <?php endif; ?>

                        <h6 class="mb-3"><strong>Información de Contacto</strong></h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Nombre:</strong><br><?php echo htmlspecialchars($orderData['customer_name'] ?? ''); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Email:</strong><br><?php echo htmlspecialchars($orderData['customer_email'] ?? ''); ?></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Teléfono:</strong><br><?php echo htmlspecialchars($orderData['customer_phone'] ?? ''); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Fecha del Pedido:</strong><br><?php echo htmlspecialchars($orderData['order_date'] ?? ''); ?></p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p><strong>Dirección de Entrega:</strong><br><?php echo htmlspecialchars($orderData['delivery_address'] ?? ''); ?></p>
                        </div>
                        <hr>

                        <h6 class="mb-3"><strong>Productos Ordenados</strong></h6>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio Unitario</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderData['items'] as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name'] ?? ''); ?></td>
                                    <td class="text-center"><?php echo $item['quantity'] ?? 1; ?></td>
                                    <td class="text-end">$<?php echo number_format($item['price'] ?? 0, 2); ?></td>
                                    <td class="text-end">$<?php echo number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <hr>
                        <?php
                            $orderTotal = floatval($orderData['total'] ?? 0);
                            $orderDelivery = floatval($orderData['delivery_fee'] ?? 0);
                            $orderSubtotal = max(0, $orderTotal - $orderDelivery);
                        ?>
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>$<?php echo number_format($orderSubtotal, 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Envío:</span>
                                    <span>$<?php echo number_format($orderDelivery, 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Total:</strong>
                                    <strong class="text-success h5">$<?php echo number_format($orderTotal, 2); ?></strong>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="alert alert-info mt-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="bi bi-box-seam"></i> 
                                    <strong>¿Quieres seguir tu pedido?</strong>
                                    <p class="mb-0 mt-2"><small>Usa tu número de orden para ver el estado en tiempo real</small></p>
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="/track-order" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i> Seguir mi Pedido
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 mb-5">
                    <a href="/menu" class="btn btn-coffee btn-lg me-2">
                        <i class="bi bi-arrow-left"></i> Seguir Comprando
                    </a>
                    <a href="/home" class="btn btn-outline-coffee btn-lg">
                        <i class="bi bi-house"></i> Ir al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
