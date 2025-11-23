<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .btn-group .btn:not(.active) { background-color: white !important; }
        .btn-group .btn-outline-primary:hover { background-color: #0d6efd !important; color: white !important; border-color: #0d6efd !important; }
        .btn-group .btn-outline-warning:hover { background-color: #ffc107 !important; color: black !important; border-color: #ffc107 !important; }
        .btn-group .btn-outline-info:hover { background-color: #0dcaf0 !important; color: black !important; border-color: #0dcaf0 !important; }
        .btn-group .btn-outline-success:hover { background-color: #198754 !important; color: white !important; border-color: #198754 !important; }
        .btn-group .btn-outline-secondary:hover { background-color: #6c757d !important; color: white !important; border-color: #6c757d !important; }
        .btn-group .btn-outline-dark:hover { background-color: #212529 !important; color: white !important; border-color: #212529 !important; }
        .dropdown-menu .dropdown-item { color: black !important; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <?php
    if (!function_exists('get_value')) {
        function get_value($src, $key, $default = null) {
            if (is_array($src)) {
                return isset($src[$key]) ? $src[$key] : $default;
            }
            if (is_object($src)) {
                return isset($src->$key) ? $src->$key : $default;
            }
            return $default;
        }
    }
    ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4"> <i class="bi bi-briefcase"></i> Gestión de Pedidos</h1>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- ALERTA DE BAJO STOCK -->
                <?php if (!empty($lowStockProducts)): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <strong>Alerta de Stock Bajo</strong>
                        <p class="mb-0 mt-2">Los siguientes productos tienen pocas unidades disponibles:</p>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($lowStockProducts as $product): ?>
                                    <?php
                                        $productName = get_value($product, 'name', 'Producto desconocido');
                                        $productStock = intval(get_value($product, 'stock', 0));
                                    ?>
                                    <li><?php echo htmlspecialchars($productName); ?> - <strong><?php echo $productStock; ?> unidades</strong></li>
                                <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <!-- FILTROS POR ESTADO -->
                <div class="btn-group" role="group">
                    <a href="/employee/orders?status=all" 
                       class="btn btn-outline-dark <?php echo ($status === 'all') ? 'active' : ''; ?>">
                        <i class="bi bi-list-check"></i> Todos
                    </a>
                    <a href="/employee/orders?status=active" 
                       class="btn btn-outline-primary <?php echo ($status === 'active') ? 'active' : ''; ?>">
                        <i class="bi bi-lightning-fill"></i> Activos
                    </a>
                    <a href="/employee/orders?status=pending" 
                       class="btn btn-outline-warning <?php echo ($status === 'pending') ? 'active' : ''; ?>">
                        <i class="bi bi-hourglass-split"></i> Pendientes
                    </a>
                    <a href="/employee/orders?status=preparing" 
                       class="btn btn-outline-info <?php echo ($status === 'preparing') ? 'active' : ''; ?>">
                        <i class="bi bi-gear-fill"></i> En Preparación
                    </a>
                    <a href="/employee/orders?status=ready" 
                       class="btn btn-outline-success <?php echo ($status === 'ready') ? 'active' : ''; ?>">
                        <i class="bi bi-check-circle-fill"></i> Listos para Retiro
                    </a>
                    <a href="/employee/orders?status=delivered" 
                       class="btn btn-outline-secondary <?php echo ($status === 'delivered') ? 'active' : ''; ?>">
                        <i class="bi bi-box-seam"></i> Entregados
                    </a>
                </div>
            </div>
        </div>

        <!-- LISTA DE PEDIDOS -->
        <div class="row">
            <div class="col-12">
                <?php if (empty($orders)): ?>
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i>
                        No hay pedidos con este estado.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash"></i> Número de Pedido</th>
                                    <th><i class="bi bi-person"></i> Cliente</th>
                                    <th><i class="bi bi-phone"></i> Teléfono</th>
                                    <th><i class="bi bi-envelope"></i> Email</th>
                                    <th><i class="bi bi-tag"></i> Estado</th>
                                    <th><i class="bi bi-cash"></i> Total</th>
                                    <th><i class="bi bi-truck"></i> Tipo Entrega</th>
                                    <th><i class="bi bi-calendar"></i> Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <?php
                                        $orderNumber = get_value($order, 'order_number', '#N/A');
                                        $customerName = get_value($order, 'customer_name', 'Anónimo');
                                        $customerPhone = get_value($order, 'customer_phone', '—');
                                        $customerEmail = get_value($order, 'customer_email', '—');
                                        $orderStatus = get_value($order, 'status', 'pending');
                                        $orderTotal = get_value($order, 'total', 0);
                                        $deliveryType = get_value($order, 'delivery_type', 'delivery');
                                        $orderDateRaw = get_value($order, 'order_date', null);
                                        $createdAtRaw = get_value($order, 'created_at', null);
                                        $orderId = (string)(get_value($order, '_id', get_value($order, '_id', '')));

                                        // Formatear fecha - priorizar order_date, luego created_at
                                        $formattedDate = date('d/m/Y H:i');
                                        if (!empty($orderDateRaw)) {
                                            $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $orderDateRaw);
                                            $formattedDate = $dateObj ? $dateObj->format('d/m/Y H:i') : $orderDateRaw;
                                        } elseif (!empty($createdAtRaw)) {
                                            if ($createdAtRaw instanceof MongoDB\BSON\UTCDateTime) {
                                                $timestamp = $createdAtRaw->toDateTime();
                                                $formattedDate = $timestamp->format('d/m/Y H:i');
                                            } else {
                                                $formattedDate = (string)$createdAtRaw;
                                            }
                                        }

                                        $statusColors = [
                                            'pending' => 'warning',
                                            'preparing' => 'info',
                                            'ready' => 'success',
                                            'delivered' => 'secondary'
                                        ];
                                        $statusColor = $statusColors[$orderStatus] ?? 'light';

                                        $deliveryLabel = ($deliveryType === 'pickup') ? 'Retiro en local' : 'A domicilio';
                                    ?>
                                    <tr>
                                        <td>
                                            <strong class="text-primary"><?php echo htmlspecialchars($orderNumber); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($customerName); ?></td>
                                        <td><?php echo htmlspecialchars($customerPhone); ?></td>
                                        <td><?php echo htmlspecialchars($customerEmail); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $statusColor; ?>">
                                                <?php 
                                                    $statusLabels = [
                                                        'pending' => 'Pendiente',
                                                        'preparing' => 'En Preparación',
                                                        'ready' => 'Listo para Retiro',
                                                        'delivered' => 'Entregado'
                                                    ];
                                                    echo $statusLabels[$orderStatus] ?? ucfirst($orderStatus);
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong>$<?php echo number_format($orderTotal, 2); ?></strong>
                                        </td>
                                        <td><?php echo $deliveryLabel; ?></td>
                                        <td>
                                            <small><?php echo htmlspecialchars($formattedDate); ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" style="flex-wrap: wrap; gap: 4px;">
                                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" 
                                                        data-bs-target="#details_<?php echo htmlspecialchars($orderId); ?>">
                                                    <i class="bi bi-eye"></i> Ver
                                                </button>

                                                <form method="POST" action="/employee/update-status" class="d-inline">
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderId); ?>">
                                                    <button type="submit" name="status" value="pending" class="btn btn-outline-warning btn-sm" title="Cambiar a Pendiente">
                                                        <i class="bi bi-hourglass-split"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="/employee/update-status" class="d-inline">
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderId); ?>">
                                                    <button type="submit" name="status" value="preparing" class="btn btn-outline-info btn-sm" title="Cambiar a En Preparación">
                                                        <i class="bi bi-gear-fill"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="/employee/update-status" class="d-inline">
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderId); ?>">
                                                    <button type="submit" name="status" value="ready" class="btn btn-outline-success btn-sm" title="Cambiar a Listo">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="/employee/update-status" class="d-inline">
                                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($orderId); ?>">
                                                    <button type="submit" name="status" value="delivered" class="btn btn-outline-secondary btn-sm" title="Cambiar a Entregado">
                                                        <i class="bi bi-box-seam"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="collapse" id="details_<?php echo htmlspecialchars($orderId); ?>">
                                        <td colspan="9">
                                            <div class="card card-body bg-light">
                                                <h6 class="mb-2"><strong>Detalles del Pedido</strong></h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Dirección de Entrega:</strong><br>
                                                            <?php echo htmlspecialchars(get_value($order, 'delivery_address', 'Retiro en local')); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Costo Envío:</strong><br>
                                                            $<?php echo number_format(get_value($order, 'delivery_fee', 0), 2); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <h6 class="mt-3 mb-2"><strong>Productos</strong></h6>
                                                <table class="table table-sm table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Producto</th>
                                                            <th class="text-center">Cantidad</th>
                                                            <th class="text-end">Precio</th>
                                                            <th class="text-end">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            $items = get_value($order, 'items', []);
                                                            
                                                            if (is_object($items)) {
                                                                $items = (array)$items;
                                                            }
                                                            
                                                            if (!is_array($items)) {
                                                                $items = [];
                                                            }
                                                            
                                                            foreach ($items as $item): 
                                                                $itemArray = is_object($item) ? (array)$item : (is_array($item) ? $item : []);
                                                                
                                                                $itemName = $itemArray['name'] ?? 'Producto sin nombre';
                                                                $itemQty = intval($itemArray['quantity'] ?? 1);
                                                                $itemPrice = floatval($itemArray['price'] ?? 0);
                                                        ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($itemName); ?></td>
                                                                <td class="text-center"><?php echo $itemQty; ?></td>
                                                                <td class="text-end">$<?php echo number_format($itemPrice, 2); ?></td>
                                                                <td class="text-end">$<?php echo number_format($itemPrice * $itemQty, 2); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>