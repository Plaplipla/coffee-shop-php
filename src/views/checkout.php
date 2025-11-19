<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div class="container mt-4">
        <h1><i class="bi bi-credit-card"></i> Finalizar Compra</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-person"></i> Información de Contacto</h5>
                        <form method="POST" action="/cart/process-order">
                            <div class="mb-3">
                                <label class="form-label">Nombre completo *</label>
                                <input type="text" name="customer_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="customer_email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Teléfono *</label>
                                <input type="tel" name="customer_phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tipo de entrega *</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="delivery_type" id="deliveryTypeDelivery" value="delivery" checked>
                                        <label class="form-check-label" for="deliveryTypeDelivery">A domicilio</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="delivery_type" id="deliveryTypePickup" value="pickup">
                                        <label class="form-check-label" for="deliveryTypePickup">Retiro en local</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="delivery-address-group">
                                <label class="form-label">Dirección de entrega *</label>
                                <textarea name="delivery_address" id="delivery_address" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="mb-3" id="pickup-address-info" style="display:none;">
                                <label class="form-label">Dirección de retiro</label>
                                <div class="alert alert-secondary mb-0">
                                    <strong>Local Cafetería Aroma</strong><br>
                                    Calle Falsa 123, Ciudad Ejemplo
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="bi bi-check-circle"></i> Confirmar Pedido
                            </button>
                        </form>
                        </div>
                    </div>
                </div>

                <script>
                (function(){
                    var deliveryRadio = document.getElementById('deliveryTypeDelivery');
                    var pickupRadio = document.getElementById('deliveryTypePickup');
                    var deliveryGroup = document.getElementById('delivery-address-group');
                    var pickupInfo = document.getElementById('pickup-address-info');
                    var deliveryField = document.getElementById('delivery_address');
                    var DELIVERY_FEE = 3000;

                    function formatCurrencyCLP(value) {
                        return '$' + new Intl.NumberFormat('es-CL', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
                    }

                    function updateDeliveryUI() {
                        var cartTotalDisplay = document.getElementById('cartTotalDisplay');
                        var deliveryFeeRow = document.getElementById('deliveryFeeRow');
                        var deliveryFeeAmount = document.getElementById('deliveryFeeAmount');
                        var baseTotal = parseFloat(cartTotalDisplay ? cartTotalDisplay.getAttribute('data-base-total') : 0) || 0;

                        if (pickupRadio && pickupRadio.checked) {
                            if (deliveryGroup) deliveryGroup.style.display = 'none';
                            if (pickupInfo) pickupInfo.style.display = '';
                            if (deliveryField) deliveryField.removeAttribute('required');
                            if (deliveryFeeRow) {
                                deliveryFeeRow.classList.add('d-none');
                                deliveryFeeRow.style.display = 'none';
                            }
                            if (deliveryFeeAmount) deliveryFeeAmount.textContent = formatCurrencyCLP(0);
                            if (cartTotalDisplay) cartTotalDisplay.textContent = formatCurrencyCLP(baseTotal);
                        } else {
                            if (deliveryGroup) deliveryGroup.style.display = '';
                            if (pickupInfo) pickupInfo.style.display = 'none';
                            if (deliveryField) deliveryField.setAttribute('required','required');
                            if (deliveryFeeRow) {
                                deliveryFeeRow.classList.remove('d-none');
                                deliveryFeeRow.style.display = '';
                            }
                            if (deliveryFeeAmount) deliveryFeeAmount.textContent = formatCurrencyCLP(DELIVERY_FEE);
                            if (cartTotalDisplay) cartTotalDisplay.textContent = formatCurrencyCLP(baseTotal + DELIVERY_FEE);
                        }
                    }

                    if (deliveryRadio) deliveryRadio.addEventListener('change', updateDeliveryUI);
                    if (pickupRadio) pickupRadio.addEventListener('change', updateDeliveryUI);
                    updateDeliveryUI();
                })();
                </script>
            
            <!-- Resumen del Pedido -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-receipt"></i> Resumen del Pedido</h5>
                        <hr>
                        
                        <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                <?php echo $item['name']; ?> 
                                <small class="text-muted">x<?php echo $item['quantity']; ?></small>
                            </span>
                            <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                        <?php endforeach; ?>
                        
                        <hr>
                        <?php
                            $baseTotal = floatval($cartTotal ?? 0);
                            $deliveryFee = 3000;
                            $initiallyDelivery = true;
                            $initialTotal = $initiallyDelivery ? ($baseTotal + $deliveryFee) : $baseTotal;
                        ?>
                        <div id="deliveryFeeRow" class="d-flex justify-content-between mb-2 <?php echo $initiallyDelivery ? '' : 'd-none'; ?>">
                            <span>Envío:</span>
                            <span id="deliveryFeeAmount" class="text-muted"><?php echo '$' . number_format($initiallyDelivery ? $deliveryFee : 0, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="cartTotalDisplay" class="h5 text-success" data-base-total="<?php echo $baseTotal; ?>"><?php echo '$' . number_format($initialTotal, 2); ?></strong>
                        </div>
                        
                        <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle"></i> 
                                <strong>Compra como invitado:</strong> No necesitas crear una cuenta. 
                                Te contactaremos para confirmar tu pedido.
                            </small>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-success">
                            <small>
                                <i class="bi bi-check-circle"></i> 
                                <strong>Sesión iniciada:</strong> Tu pedido será registrado en tu cuenta.
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>