<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Pedido - Café Aroma</title>
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
        .order-tracking-container {
            flex: 1;
            padding: 2rem 1rem;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <div class="order-tracking-container">
        <h1 class="mb-4 text-center">
            <i class="bi bi-box-seam"></i> Seguimiento de Pedido
        </h1>

        <?php if (!isset($order) || $order === null): ?>
            <!-- Formulario de búsqueda -->
            <div class="search-section">
                <h3 class="mb-4">Ingresa tu número de orden para ver el estado</h3>
                <form method="POST" action="/track-order">
                    <div class="input-group mb-3">
                        <input type="text" 
                               class="form-control form-control-lg" 
                               name="order_number" 
                               placeholder="Ej: ORD-5f2d7a9b3e1c7"
                               required>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </form>

                <?php if (isset($notFound) && $notFound): ?>
                    <div class="alert alert-warning mt-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        No se encontró un pedido con el número <strong><?php echo htmlspecialchars($_POST['order_number'] ?? ''); ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Información del pedido encontrado -->
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                ¡Pedido encontrado! Aquí está el estado actual de tu orden.
            </div>

            <!-- Estado del pedido (actualización automática) -->
            <div id="statusAlert" class="alert alert-info" role="alert">
                <i class="bi bi-info-circle"></i>
                <strong id="statusLabel">
                    <?php
                        $statusMessages = [
                            'pending' => 'Estado del Pedido: Pendiente de Confirmación',
                            'preparing' => 'Estado del Pedido: En Preparación',
                            'ready' => 'Estado del Pedido: ¡Listo!',
                            'delivered' => 'Estado del Pedido: Completado'
                        ];
                        echo $statusMessages[$order->status] ?? 'Estado del Pedido: Pendiente de Confirmación';
                    ?>
                </strong>
                <p id="statusMessage" class="mb-0 mt-2">
                    <?php
                        $detailMessages = [
                            'pending' => 'Nos comunicaremos contigo en breve para confirmar tu pedido y proporcionar más detalles sobre la entrega.',
                            'preparing' => 'Tu pedido está siendo preparado. ¡Estará listo pronto!',
                            'ready' => $order->delivery_type === 'pickup' ? '¡Tu pedido está listo para retiro! Puedes pasar a buscarlo cuando gustes.' : '¡Tu pedido está listo y será entregado en breve!',
                            'delivered' => $order->delivery_type === 'pickup' ? '¡Gracias por tu compra! Tu pedido ha sido retirado.' : '¡Tu pedido ha sido entregado! Gracias por tu compra.'
                        ];
                        echo $detailMessages[$order->status] ?? 'Actualizando estado del pedido...';
                    ?>
                </p>
            </div>

            <!-- Timeline de estados -->
            <div class="status-timeline">
                <div class="status-item <?php echo in_array($order->status, ['pending', 'preparing', 'ready', 'delivered']) ? 'completed' : ''; ?>" id="status-pending">
                    <div class="status-circle">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="status-label">Pendiente</div>
                </div>
                
                <div class="status-item <?php echo in_array($order->status, ['preparing', 'ready', 'delivered']) ? 'completed' : ''; ?> <?php echo $order->status === 'preparing' ? 'active' : ''; ?>" id="status-preparing">
                    <div class="status-circle">
                        <i class="bi bi-gear-fill"></i>
                    </div>
                    <div class="status-label">En Preparación</div>
                </div>
                
                <div class="status-item <?php echo in_array($order->status, ['ready', 'delivered']) ? 'completed' : ''; ?> <?php echo $order->status === 'ready' ? 'active' : ''; ?>" id="status-ready">
                    <div class="status-circle">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="status-label">Listo para <?php echo $order->delivery_type === 'pickup' ? 'Retiro' : 'Entrega'; ?></div>
                </div>
                
                <div class="status-item <?php echo $order->status === 'delivered' ? 'completed active' : ''; ?>" id="status-delivered">
                    <div class="status-circle">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="status-label"><?php echo $order->delivery_type === 'pickup' ? 'Retirado' : 'Entregado'; ?></div>
                </div>
            </div>

            <!-- Detalles del pedido -->
            <div class="order-details">
                <h5 class="mb-3">
                    <i class="bi bi-info-circle"></i> Detalles del Pedido
                </h5>
                
                <div class="detail-row">
                    <span class="detail-label">Número de Orden:</span>
                    <span class="detail-value"><strong><?php echo htmlspecialchars($order->order_number); ?></strong></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Estado Actual:</span>
                    <span class="detail-value">
                        <?php
                            $statusLabels = [
                                'pending' => 'Pendiente',
                                'preparing' => 'En Preparación',
                                'ready' => 'Listo para ' . ($order->delivery_type === 'pickup' ? 'Retiro' : 'Entrega'),
                                'delivered' => $order->delivery_type === 'pickup' ? 'Retirado' : 'Entregado'
                            ];
                            echo $statusLabels[$order->status] ?? ucfirst($order->status);
                        ?>
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Total:</span>
                    <span class="detail-value">$<?php echo number_format($order->total, 2); ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Tipo de Entrega:</span>
                    <span class="detail-value">
                        <?php echo $order->delivery_type === 'pickup' ? 'Retiro en Local' : 'A Domicilio'; ?>
                    </span>
                </div>

                <?php if ($order->delivery_type === 'delivery'): ?>
                    <div class="detail-row">
                        <span class="detail-label">Dirección de Entrega:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($order->delivery_address ?? 'No especificada'); ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Costo de Envío:</span>
                        <span class="detail-value">$<?php echo number_format($order->delivery_fee ?? 0, 2); ?></span>
                    </div>
                <?php endif; ?>

                <div class="detail-row">
                    <span class="detail-label">Fecha del Pedido:</span>
                    <span class="detail-value">
                        <?php
                            $formattedDate = 'Fecha no disponible';
                            
                            // Intentar formatear order_date primero
                            if (isset($order->order_date) && !empty($order->order_date)) {
                                $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $order->order_date);
                                $formattedDate = $dateObj ? $dateObj->format('d/m/Y H:i') : $order->order_date;
                            }
                            // Si no, intentar formatear created_at (MongoDB UTCDateTime)
                            elseif (isset($order->created_at) && !empty($order->created_at)) {
                                if ($order->created_at instanceof MongoDB\BSON\UTCDateTime) {
                                    $timestamp = $order->created_at->toDateTime();
                                    $formattedDate = $timestamp->format('d/m/Y H:i');
                                } else {
                                    $formattedDate = (string)$order->created_at;
                                }
                            }
                            
                            echo htmlspecialchars($formattedDate);
                        ?>
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Nombre:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order->customer_name ?? 'No especificado'); ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Teléfono:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order->customer_phone ?? 'No especificado'); ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order->customer_email ?? 'No especificado'); ?></span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Método de Pago:</span>
                    <span class="detail-value">
                        <?php 
                            $paymentMethod = $order->payment_method ?? 'card';
                            if ($paymentMethod === 'card'): ?>
                                <i class="bi bi-credit-card"></i> Tarjeta al Recibir
                            <?php elseif ($paymentMethod === 'cash'): ?>
                                <i class="bi bi-cash-coin"></i> Efectivo
                            <?php else: ?>
                                <?php echo htmlspecialchars(ucfirst($paymentMethod)); ?>
                            <?php endif; ?>
                    </span>
                </div>

                <?php if (!empty($order->discount_code) || !empty($order->discount_amount)): ?>
                <div class="detail-row">
                    <span class="detail-label">Código de Descuento:</span>
                    <span class="detail-value">
                        <?php echo htmlspecialchars($order->discount_code ?? '-'); ?> 
                        <span class="text-success">-$<?php echo number_format($order->discount_amount ?? 0, 2); ?></span>
                    </span>
                </div>
                <?php endif; ?>

            <!-- Productos del pedido -->
            <div class="order-details mt-4">
                <h5 class="mb-3">
                    <i class="bi bi-basket"></i> Productos
                </h5>
                <table class="table table-sm">
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
                            $items = isset($order->items) ? $order->items : [];
                            
                            // Convertir items a array si es necesario
                            if (is_object($items)) {
                                $items = (array)$items;
                            }
                            
                            if (!is_array($items)) {
                                $items = [];
                            }
                            
                            foreach ($items as $item): 
                                // Convertir item a array si es objeto
                                $itemArray = is_object($item) ? (array)$item : (is_array($item) ? $item : []);
                                
                                $itemName = $itemArray['name'] ?? $itemArray['cart_item_key'] ?? 'Producto sin nombre';
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

            <div class="text-center mt-4">
                <a href="/track-order" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar Otro Pedido
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Actualizar estado del pedido automáticamente cada 5 segundos
    <?php if (isset($order) && $order): ?>
    
    document.addEventListener('DOMContentLoaded', function() {
        const orderNumber = '<?php echo isset($order->order_number) ? htmlspecialchars($order->order_number) : ''; ?>';
        const deliveryType = '<?php echo isset($order->delivery_type) ? htmlspecialchars($order->delivery_type) : 'delivery'; ?>';
        let lastStatus = '<?php echo isset($order->status) ? htmlspecialchars($order->status) : 'pending'; ?>';

        console.log('✓ DOM Cargado - Iniciando polling para orden: ' + orderNumber);
        console.log('Estado inicial: ' + lastStatus);

        // Mensajes según estado
        const statusLabels = {
            'pending': 'Estado del Pedido: Pendiente de Confirmación',
            'preparing': 'Estado del Pedido: En Preparación',
            'ready': 'Estado del Pedido: ¡Listo!',
            'delivered': 'Estado del Pedido: Completado'
        };

        const statusMessages = {
            'pending': 'Nos comunicaremos contigo en breve para confirmar tu pedido y proporcionar más detalles sobre la entrega.',
            'preparing': 'Tu pedido está siendo preparado. ¡Estará listo pronto!',
            'ready': deliveryType === 'pickup' 
                ? '¡Tu pedido está listo para retiro! Puedes pasar a buscarlo cuando gustes.'
                : '¡Tu pedido está listo y será entregado en breve!',
            'delivered': deliveryType === 'pickup' 
                ? '¡Gracias por tu compra! Tu pedido ha sido retirado.'
                : '¡Tu pedido ha sido entregado! Gracias por tu compra.'
        };

        const alertClasses = {
            'pending': 'alert-info',
            'preparing': 'alert-warning',
            'ready': 'alert-success',
            'delivered': 'alert-success'
        };

        const alertIcons = {
            'pending': 'bi-info-circle',
            'preparing': 'bi-hourglass-split',
            'ready': 'bi-check-circle-fill',
            'delivered': 'bi-check-circle-fill'
        };

        function updateStatusUI(newStatus) {
            console.log('📝 Actualizando UI con nuevo estado: ' + newStatus);
            
            const statusAlert = document.getElementById('statusAlert');
            const statusLabel = document.getElementById('statusLabel');
            const statusMessage = document.getElementById('statusMessage');
            
            if (!statusAlert) {
                console.warn('⚠️ No se encontró elemento #statusAlert');
                return;
            }

            const alertIcon = statusAlert.querySelector('i');
            
            if (!statusLabel || !statusMessage || !alertIcon) {
                console.warn('⚠️ Falta algún elemento del DOM');
                return;
            }

            // Actualizar clase de alerta
            Object.values(alertClasses).forEach(cls => statusAlert.classList.remove(cls));
            statusAlert.classList.add(alertClasses[newStatus]);

            // Actualizar icono
            Object.values(alertIcons).forEach(icon => alertIcon.classList.remove(icon));
            alertIcon.classList.add(alertIcons[newStatus]);

            // Actualizar texto
            statusLabel.textContent = statusLabels[newStatus];
            statusMessage.textContent = statusMessages[newStatus];

            // Actualizar timeline
            updateTimeline(newStatus);
            
            console.log('✅ UI actualizada correctamente');
        }

        function updateTimeline(newStatus) {
            const statusOrder = ['pending', 'preparing', 'ready', 'delivered'];
            const statusIndex = statusOrder.indexOf(newStatus);

            statusOrder.forEach((status, index) => {
                const item = document.getElementById('status-' + status);
                if (item) {
                    if (index < statusIndex) {
                        item.classList.add('completed');
                        item.classList.remove('active');
                    } else if (index === statusIndex) {
                        item.classList.add('completed', 'active');
                    } else {
                        item.classList.remove('completed', 'active');
                    }
                }
            });
            
            console.log('📊 Timeline actualizado');
        }

        function updateOrderStatus() {
            if (!orderNumber) {
                console.warn('⚠️ No hay número de orden para consultar');
                return;
            }

            const formData = new FormData();
            formData.append('order_number', orderNumber);

            console.log('🔄 Consultando estado del servidor...');

            fetch('/track-order', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                console.log('📦 Respuesta del servidor recibida');
                
                // Extraer el estado actual del HTML devuelto
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');
                
                // Buscar los elementos de estado
                const statusItems = newDoc.querySelectorAll('.status-item');
                let newStatus = 'pending'; // por defecto
                
                // Determinar el nuevo estado según qué elementos estén completados
                if (statusItems.length >= 4) {
                    // Buscar cuál es el índice más alto completado
                    for (let i = 3; i >= 0; i--) {
                        if (statusItems[i].classList.contains('completed')) {
                            if (i === 3) newStatus = 'delivered';
                            else if (i === 2) newStatus = 'ready';
                            else if (i === 1) newStatus = 'preparing';
                            else newStatus = 'pending';
                            break;
                        }
                    }
                }
                
                console.log('🔍 Nuevo estado detectado: ' + newStatus);
                
                // Si el estado cambió, actualizar la interfaz sin recargar
                if (newStatus !== lastStatus) {
                    console.log('✅ Estado cambió de "' + lastStatus + '" a "' + newStatus + '"');
                    lastStatus = newStatus;
                    updateStatusUI(newStatus);
                } else {
                    console.log('⏳ Sin cambios en el estado');
                }
            })
            .catch(error => console.error('❌ Error actualizando estado:', error));
        }

        // Llamar inmediatamente al cargar
        console.log('🚀 Primera consulta inmediata...');
        updateOrderStatus();

        // Actualizar cada 5 segundos
        let pollInterval = setInterval(updateOrderStatus, 5000);
        console.log('✓ Polling iniciado - Actualizando cada 5 segundos');

        // También actualizar cuando la pestaña vuelve a ser visible
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                console.log('📍 Pestaña visible - Actualizando inmediatamente');
                updateOrderStatus();
            } else {
                console.log('⏸️ Pestaña oculta - Pausando polling');
            }
        });
    });
    
    <?php endif; ?>
    </script>

    <?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
