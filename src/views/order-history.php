<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Pedidos - Cafetería Aroma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main class="container mt-4 mb-5">
        <h1><i class="bi bi-receipt"></i> Historial de Pedidos</h1>
        
        <!-- Mensajes de éxito/error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-light border-start border-4 border-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill text-success me-2"></i> 
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-light border-start border-4 border-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> 
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($userOrders)): ?>
            <div class="alert bg-white text-center">
                <i class="bi bi-inbox product-icon"></i>
                <h4 class="mt-3">Aún no tienes pedidos</h4>
                <p>¡Realiza tu primer pedido y aparecerá aquí!</p>
                <a href="/menu" class="btn btn-coffee">
                    <i class="bi bi-bag-check" style="font-size: 1rem;"></i> Ir al Menú
                </a>
            </div>
        <?php else: ?>
            <div class="mt-4">
                <?php foreach ($userOrders as $order): ?>
                    <?php
                        // Compatibilidad con array y objeto
                        $orderNumber = is_object($order) ? ($order->order_number ?? '') : ($order['order_number'] ?? '');
                        $orderDate = is_object($order) ? ($order->order_date ?? '') : ($order['order_date'] ?? '');
                        $createdAt = is_object($order) ? ($order->created_at ?? '') : ($order['created_at'] ?? '');
                        $status = is_object($order) ? ($order->status ?? '') : ($order['status'] ?? '');
                        $total = is_object($order) ? ($order->total ?? 0) : ($order['total'] ?? 0);
                        $items = is_object($order) ? ($order->items ?? []) : ($order['items'] ?? []);
                        $customerName = is_object($order) ? ($order->customer_name ?? '') : ($order['customer_name'] ?? '');
                        $deliveryType = is_object($order) ? ($order->delivery_type ?? '') : ($order['delivery_type'] ?? '');
                        $paymentMethod = is_object($order) ? ($order->payment_method ?? 'card') : ($order['payment_method'] ?? 'card');
                        $discountCode = is_object($order) ? ($order->discount_code ?? '') : ($order['discount_code'] ?? '');
                        $discountAmount = is_object($order) ? ($order->discount_amount ?? 0) : ($order['discount_amount'] ?? 0);

                        // Convertir items a array si es necesario
                        if (is_object($items)) {
                            $items = (array)$items;
                        }
                        $itemCount = count($items);
                        
                        // Obtener descripción del estado
                        $statusLabel = match($status) {
                            'pending' => 'Pendiente',
                            'preparing' => 'En Preparación',
                            'ready' => 'Listo',
                            'delivered' => 'Entregado',
                            default => ucfirst($status)
                        };
                        
                        // Mapear estado a clase de Bootstrap
                        $statusClass = match($status) {
                            'pending' => 'warning',
                            'preparing' => 'info',
                            'ready' => 'success',
                            'delivered' => 'success',
                            default => 'secondary'
                        };
                        
                        // Formatear fecha - priorizar order_date, luego created_at
                        $formattedDate = '';
                        if (!empty($orderDate)) {
                            $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $orderDate);
                            $formattedDate = $dateObj ? $dateObj->format('d/m/Y H:i') : $orderDate;
                        } elseif (!empty($createdAt)) {
                            // Si createdAt es un objeto MongoDB UTCDateTime
                            if (is_object($createdAt) && $createdAt instanceof MongoDB\BSON\UTCDateTime) {
                                $timestamp = $createdAt->toDateTime();
                                $formattedDate = $timestamp->format('d/m/Y H:i');
                            } else {
                                $formattedDate = (string)$createdAt;
                            }
                        } else {
                            $formattedDate = 'Fecha no disponible';
                        }
                    ?>
                    <div class="card mb-3 border-start border-4 border-coffee">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <p class="mb-1 fw-bold text-coffee">
                                        <i class="bi bi-hash"></i> <?php echo htmlspecialchars($orderNumber); ?>
                                    </p>
                                    <p class="mb-0">
                                        <small class="text-muted"><i class="bi bi-calendar"></i> <?php echo htmlspecialchars($formattedDate); ?></small>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-1"><?php echo $itemCount; ?> <?php echo ($itemCount === 1) ? 'artículo' : 'artículos'; ?></small>
                                    <small class="text-muted">
                                        <?php
                                            $itemNames = [];
                                            foreach ($items as $item) {
                                                $itemObj = is_object($item) ? $item : (object)$item;
                                                $itemNames[] = htmlspecialchars($itemObj->name ?? 'Producto desconocido');
                                            }
                                            echo implode(', ', array_slice($itemNames, 0, 2));
                                            if (count($itemNames) > 2) {
                                                echo ' +' . (count($itemNames) - 2);
                                            }
                                        ?>
                                    </small>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge bg-<?php echo $statusClass; ?>">
                                        <?php echo $statusLabel; ?>
                                    </span>
                                </div>
                                <div class="col-md-3 text-end">
                                    <h6 class="mb-1 fw-bold text-coffee">$<?php echo number_format($total, 0, ',', '.'); ?></h6>
                                    <small class="text-muted d-block">
                                        <?php if ($paymentMethod === 'card'): ?>
                                            <i class="bi bi-credit-card"></i> Tarjeta
                                        <?php elseif ($paymentMethod === 'cash'): ?>
                                            <i class="bi bi-cash-coin"></i> Efectivo
                                        <?php else: ?>
                                            <?php echo htmlspecialchars($paymentMethod); ?>
                                        <?php endif; ?>
                                    </small>
                                    <small class="text-muted d-block"><?php echo ($deliveryType === 'pickup') ? 'Retiro' : 'Delivery'; ?></small>
                                    <?php if (!empty($discountCode) && $discountAmount > 0): ?>
                                    <small class="text-success d-block"><i class="bi bi-tag"></i> Descuento: -$<?php echo number_format($discountAmount, 0, ',', '.'); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-4 text-center">
                <a href="/menu" class="btn btn-coffee">
                    <i class="bi bi-plus-circle" style="font-size: 1rem;"></i> Realizar otro pedido
                </a>
            </div>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
