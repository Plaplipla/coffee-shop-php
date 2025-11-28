<?php require_once __DIR__ . '/../partials/header-delivery.php'; ?>

<div class="container-fluid mt-3 px-2 px-md-4">
    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <h2 class="h4 mb-2"><i class="bi bi-truck"></i> Pedidos para Entrega</h2>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-warning text-dark">
                        <i class="bi bi-box-seam"></i> Listos: <?php echo $stats['ready']; ?>
                    </span>
                    <span class="badge bg-primary">
                        <i class="bi bi-truck"></i> En Camino: <?php echo $stats['in_transit']; ?>
                    </span>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Hoy: <?php echo $stats['delivered_today']; ?>
                    </span>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-body p-2">
                    <div class="btn-group-vertical btn-group-sm d-md-none w-100" role="group">
                        <a href="/delivery/orders?status=ready" class="btn btn-outline-warning <?php echo ($status === 'ready') ? 'active' : ''; ?>">
                            <i class="bi bi-box-seam"></i> Listos para Entrega
                        </a>
                        <a href="/delivery/orders?status=in-transit" class="btn btn-outline-primary <?php echo ($status === 'in-transit') ? 'active' : ''; ?>">
                            <i class="bi bi-truck"></i> En Camino
                        </a>
                        <a href="/delivery/orders?status=delivered" class="btn btn-outline-success <?php echo ($status === 'delivered') ? 'active' : ''; ?>">
                            <i class="bi bi-check-circle"></i> Entregados
                        </a>
                        <a href="/delivery/orders?status=all" class="btn btn-outline-secondary <?php echo ($status === 'all') ? 'active' : ''; ?>">
                            <i class="bi bi-list"></i> Todos
                        </a>
                    </div>
                    <div class="btn-group d-none d-md-flex" role="group">
                        <a href="/delivery/orders?status=ready" class="btn btn-outline-warning <?php echo ($status === 'ready') ? 'active' : ''; ?>">
                            <i class="bi bi-box-seam"></i> Listos para Entrega
                        </a>
                        <a href="/delivery/orders?status=in-transit" class="btn btn-outline-primary <?php echo ($status === 'in-transit') ? 'active' : ''; ?>">
                            <i class="bi bi-truck"></i> En Camino
                        </a>
                        <a href="/delivery/orders?status=delivered" class="btn btn-outline-success <?php echo ($status === 'delivered') ? 'active' : ''; ?>">
                            <i class="bi bi-check-circle"></i> Entregados
                        </a>
                        <a href="/delivery/orders?status=all" class="btn btn-outline-secondary <?php echo ($status === 'all') ? 'active' : ''; ?>">
                            <i class="bi bi-list"></i> Todos
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Lista de Pedidos -->
            <?php if (empty($orders)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No hay pedidos para mostrar en este momento.
                </div>
            <?php else: ?>
                <div class="row g-2 g-md-3">
                    <?php foreach ($orders as $order): 
                        $order = (array)$order; // Convertir objeto a array
                    ?>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card h-100 shadow-sm order-card" data-status="<?php echo $order['status']; ?>">
                                <div class="card-header d-flex justify-content-between align-items-center 
                                    <?php 
                                        echo $order['status'] === 'ready' ? 'bg-warning text-dark' : 
                                            ($order['status'] === 'in-transit' ? 'bg-primary text-white' : 'bg-success text-white');
                                    ?>">
                                    <strong>#<?php echo htmlspecialchars($order['order_number']); ?></strong>
                                    <span class="badge bg-light text-dark">
                                        <?php 
                                            echo $order['status'] === 'ready' ? 'Listo' : 
                                                ($order['status'] === 'in-transit' ? 'En Camino' : 'Entregado');
                                        ?>
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-2">
                                        <h5 class="card-title h6 mb-1">
                                            <i class="bi bi-person"></i> <?php echo htmlspecialchars($order['customer_name']); ?>
                                        </h5>
                                        <p class="mb-0 small">
                                            <i class="bi bi-telephone"></i> 
                                            <a href="tel:<?php echo htmlspecialchars($order['customer_phone']); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($order['customer_phone']); ?>
                                            </a>
                                        </p>
                                    </div>
                                    
                                    <div class="mb-2 bg-light p-2 rounded">
                                        <strong class="small"><i class="bi bi-geo-alt-fill"></i> Dirección:</strong><br>
                                        <span class="small"><?php echo htmlspecialchars($order['delivery_address']); ?></span>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($order['delivery_address']); ?>" 
                                           target="_blank" class="btn btn-sm btn-outline-primary mt-1 w-100">
                                            <i class="bi bi-map"></i> Ver en Mapa
                                        </a>
                                    </div>
                                    
                                    <!-- Productos -->
                                    <div class="mb-2">
                                        <strong class="small"><i class="bi bi-bag"></i> Productos:</strong>
                                        <ul class="list-unstyled mt-1 mb-0">
                                            <?php foreach ($order['items'] as $item): 
                                                $item = (array)$item; // Convertir objeto a array
                                            ?>
                                                <li class="small">
                                                    <?php echo $item['quantity']; ?>x 
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    
                                    <!-- Total y Fecha -->
                                    <div class="d-flex justify-content-between align-items-center mb-2 py-2 border-top">
                                        <div>
                                            <strong class="small">Total:</strong><br>
                                            <span class="text-success fs-5 fw-bold">
                                                $<?php echo number_format($order['total'], 0, ',', '.'); ?>
                                            </span>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block">
                                                <i class="bi bi-clock"></i> 
                                                <?php 
                                                    if (isset($order['created_at'])) {
                                                        if (is_object($order['created_at']) && method_exists($order['created_at'], 'toDateTime')) {
                                                            echo $order['created_at']->toDateTime()->format('d/m H:i');
                                                        } else {
                                                            echo date('d/m H:i', strtotime($order['created_at']));
                                                        }
                                                    }
                                                ?>
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Botones de Acción -->
                                    <?php if ($order['status'] === 'ready'): ?>
                                        <button class="btn btn-primary w-100 btn-lg update-status-btn" 
                                                data-order-id="<?php echo $order['_id']; ?>" 
                                                data-status="in-transit">
                                            <i class="bi bi-truck"></i> Iniciar Entrega
                                        </button>
                                    <?php elseif ($order['status'] === 'in-transit'): ?>
                                        <button class="btn btn-success w-100 btn-lg update-status-btn" 
                                                data-order-id="<?php echo $order['_id']; ?>" 
                                                data-status="delivered">
                                            <i class="bi bi-check-circle"></i> Marcar como Entregado
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.order-card {
    transition: transform 0.2s;
    border: 2px solid #ddd;
}
.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
}

/* Mejoras para móvil */
@media (max-width: 767px) {
    .order-card {
        font-size: 0.95rem;
    }
    .order-card .card-header {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    .order-card .card-body {
        padding: 0.75rem !important;
    }
    .order-card .btn-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-weight: 600;
    }
    h2, .h4 {
        font-size: 1.25rem !important;
    }
    .badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.6rem;
    }
    .btn-group-vertical .btn {
        padding: 0.6rem;
        font-size: 0.9rem;
    }
}

/* Enlaces de teléfono más grandes en móvil */
@media (max-width: 767px) {
    a[href^="tel:"] {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0d6efd !important;
    }
}

/* Botón de mapa más visible */
.btn-outline-primary {
    border-width: 2px;
}
</style>

<script>
// Actualizar estado de pedido
document.querySelectorAll('.update-status-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const orderId = this.dataset.orderId;
        const newStatus = this.dataset.status;
        const statusText = newStatus === 'in-transit' ? 'En Camino' : 'Entregado';
        
        if (!confirm(`¿Marcar pedido como ${statusText}?`)) return;
        
        try {
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('status', newStatus);
            
            const response = await fetch('/delivery/update-status', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            alert('Error de conexión');
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/alerts.js"></script>
</body>
</html>
