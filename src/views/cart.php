<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <div class="container mt-4">
        <h1><i class="bi bi-cart3"></i> Tu Carrito de Compras</h1>
        
        <!-- Mensajes de éxito/error -->
        <div class="container mt-4">
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
        </div>

        <?php
        // Calcular totales desde la sesión
        $cartItems = $_SESSION['cart'] ?? [];
        $cartTotal = 0;
        $itemCount = 0;
        
        foreach ($cartItems as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
            $itemCount += $item['quantity'];
        }
        ?>

        <?php if (empty($cartItems)): ?>
            <div class="empty-block bg-white text-center">
                <i class="bi bi-cart-x product-icon"></i>
                <h4 class="mt-3">Tu carrito está vacío</h4>
                <p>¡Descubre nuestros deliciosos cafés!</p>
                <a href="/menu" class="btn btn-coffee">
                    <i class="bi bi-cup-hot"></i> Ir a Comprar
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?php echo $item['image']; ?>" 
                                             alt="<?php echo $item['name']; ?>" 
                                             class="cart-item-image">
                                    <?php else: ?>
                                        <i class="<?php echo $item['icon'] ?? 'bi bi-cup-hot'; ?> product-icon"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="card-title">
                                        <?php echo htmlspecialchars($item['name']); ?>
                                        <?php 
                                        // Mostrar extras si existen
                                        $extras = json_decode($item['extras'] ?? '{}', true);
                                        $extrasLabels = [
                                            'descafeinado' => 'Descafeinado',
                                            'extraShot' => 'Extra shot',
                                            'syrupVainilla' => 'Syrup Vainilla',
                                            'syrupChocolate' => 'Syrup Chocolate'
                                        ];
                                        $selectedExtras = [];
                                        foreach ($extras as $key => $quantity) {
                                            if ($quantity > 0 && isset($extrasLabels[$key])) {
                                                $selectedExtras[] = $extrasLabels[$key] . ' x' . $quantity;
                                            }
                                        }
                                        if (!empty($selectedExtras)) {
                                            echo '<br><small class="text-muted">' . implode(', ', $selectedExtras) . '</small>';
                                        }
                                        ?>
                                    </h5>
                                </div>
                                <div class="col-md-2">
                                    <span class="h6">$<?php echo number_format($item['base_price'] ?? $item['price'], 0, ',', '.'); ?></span>
                                    <small class="text-muted d-block">c/u</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <form method="POST" action="/cart/update-quantity" style="display: flex; align-items: center; gap: 5px;">
                                            <input type="hidden" name="cart_item_key" value="<?php echo htmlspecialchars($item['cart_item_key']); ?>">
                                            <input type="hidden" name="quantity" value="<?php echo $item['quantity'] - 1; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Disminuir" <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>−</button>
                                        </form>
                                        <span class="quantity-display" style="min-width: 30px; text-align: center; font-weight: bold;"><?php echo $item['quantity']; ?></span>
                                        <form method="POST" action="/cart/update-quantity" style="display: flex; align-items: center; gap: 5px;">
                                            <input type="hidden" name="cart_item_key" value="<?php echo htmlspecialchars($item['cart_item_key']); ?>">
                                            <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Aumentar" <?php echo $item['quantity'] >= 10 ? 'disabled' : ''; ?>>+</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <strong class="h6">$<?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></strong>
                                    <form method="POST" action="/cart/remove" class="mt-2">
                                        <input type="hidden" name="cart_item_key" value="<?php echo htmlspecialchars($item['cart_item_key']); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-md-4">
                    <div class="card sticky-top" style="top: 20px;">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-receipt"></i> Resumen de Compra</h5>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Productos:</span>
                                <span><?php echo $itemCount; ?> items</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong class="h5 text-success">$<?php echo number_format($cartTotal, 0, ',', '.'); ?></strong>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="/checkout" class="btn btn-success btn-lg">
                                    <i class="bi bi-credit-card"></i> Proceder al Pago
                                </a>
                                
                                <form method="POST" action="/cart/clear">
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="bi bi-trash"></i> Vaciar Carrito
                                    </button>
                                </form>
                                
                                <a href="/menu" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left"></i> Seguir Comprando
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>