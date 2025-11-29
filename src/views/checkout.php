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
    <?php include __DIR__ . '/partials/header.php'; ?>

    <div class="container mt-4">
        <h1><i class="bi bi-credit-card"></i> Finalizar Compra</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_email']) && isset($isFirstOrder) && $isFirstOrder): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-gift"></i>
            <strong>¡Bienvenido a Coffee Shop!</strong> Tienes un descuento de bienvenida del 15% en tu primer pedido. 
            Usa el código <strong>WELCOME15</strong> para aplicarlo.
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
                                <input type="text" name="customer_name" class="form-control" value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <?php if (isset($_SESSION['user_email'])): ?>
                                    <input type="email" name="customer_email" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" readonly>
                                    <small class="form-text text-muted">Email de tu cuenta</small>
                                <?php else: ?>
                                        <input type="email" name="customer_email" id="customerEmailInput" class="form-control" required>
                                        <small id="emailEligibility" class="form-text text-muted" style="display:none;"></small>
                                <?php endif; ?>
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

                            <div class="mb-3">
                                <label class="form-label">Método de Pago *</label>
                                <div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="payment_method" id="paymentStripe" value="stripe" checked>
                                        <label class="form-check-label" for="paymentStripe">
                                            <i class="bi bi-credit-card-2-front"></i> <strong>Pagar Online con Stripe</strong>
                                            <small class="d-block text-muted ms-4">Pago seguro con tarjeta de crédito/débito</small>
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="payment_method" id="paymentCard" value="card">
                                        <label class="form-check-label" for="paymentCard">
                                            <i class="bi bi-credit-card"></i> Tarjeta al Recibir
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="payment_method" id="paymentCash" value="cash">
                                        <label class="form-check-label" for="paymentCash">
                                            <i class="bi bi-cash-coin"></i> Efectivo
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="delivery-address-group">
                                <label class="form-label">Dirección de entrega *</label>
                                <div class="input-group mb-2">
                                    <textarea name="delivery_address" id="delivery_address" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnVerifyAddress">
                                        <i class="bi bi-geo-alt"></i> Verificar dirección
                                    </button>
                                    <span id="verifyStatus" class="badge bg-secondary">No verificada</span>
                                </div>
                                <div id="verifyMessage" class="text-muted small" style="display:none;"></div>
                                <div id="addressMap" style="height: 180px; border-radius: 8px; overflow: hidden; display:none;" class="mt-2"></div>
                                <input type="hidden" name="delivery_lat" id="delivery_lat" value="">
                                <input type="hidden" name="delivery_lng" id="delivery_lng" value="">
                                <input type="hidden" name="delivery_address_normalized" id="delivery_address_normalized" value="">
                            </div>

                            <div class="mb-3" id="pickup-address-info" style="display:none;">
                                <label class="form-label">Dirección de retiro</label>
                                <div class="alert alert-secondary mb-0">
                                    <strong>Local Cafetería Aroma</strong><br>
                                    Calle 123, Ciudad Ejemplo
                                </div>
                            </div>
                            <button type="submit" id="submitOrderBtn" class="btn btn-success btn-lg w-100">
                                <i class="bi bi-check-circle"></i> Confirmar Pedido
                            </button>
                            <input type="hidden" id="discountCodeHidden" name="discount_code" value="">
                            <input type="hidden" id="discountPercentageHidden" name="discount_percentage" value="0">
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
                    var discountPercentage = 0;

                    function formatCurrencyCLP(value) {
                        return '$' + new Intl.NumberFormat('es-CL', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
                    }

                    function updateTotal() {
                        var cartTotalDisplay = document.getElementById('cartTotalDisplay');
                        var subtotalDisplay = document.getElementById('subtotalDisplay');
                        var productSubtotal = parseFloat(cartTotalDisplay ? cartTotalDisplay.getAttribute('data-subtotal') : 0) || 0;
                        var deliveryFee = (deliveryRadio && deliveryRadio.checked) ? DELIVERY_FEE : 0;
                        
                        var subtotal = productSubtotal + deliveryFee;
                        
                        var discountAmount = (productSubtotal * discountPercentage) / 100;
                        var total = subtotal - discountAmount;
                        
                        if (subtotalDisplay) subtotalDisplay.textContent = formatCurrencyCLP(subtotal);
                        cartTotalDisplay.textContent = formatCurrencyCLP(total);
                        
                        var discountRow = document.getElementById('discountRow');
                        var discountAmountDisplay = document.getElementById('discountAmount');
                        if (discountPercentage > 0) {
                            discountRow.style.display = '';
                            discountAmountDisplay.textContent = '-' + formatCurrencyCLP(discountAmount);
                        } else {
                            discountRow.style.display = 'none';
                        }
                    }

                    function updateDeliveryUI() {
                        var deliveryGroup = document.getElementById('delivery-address-group');
                        var pickupInfo = document.getElementById('pickup-address-info');
                        var deliveryField = document.getElementById('delivery_address');
                        var deliveryFeeRow = document.getElementById('deliveryFeeRow');
                        var deliveryFeeAmount = document.getElementById('deliveryFeeAmount');

                        if (pickupRadio && pickupRadio.checked) {
                            if (deliveryGroup) deliveryGroup.style.display = 'none';
                            if (pickupInfo) pickupInfo.style.display = '';
                            if (deliveryField) deliveryField.removeAttribute('required');
                            if (deliveryFeeRow) {
                                deliveryFeeRow.classList.add('d-none');
                                deliveryFeeRow.style.display = 'none';
                            }
                            if (deliveryFeeAmount) deliveryFeeAmount.textContent = formatCurrencyCLP(0);
                        } else {
                            if (deliveryGroup) deliveryGroup.style.display = '';
                            if (pickupInfo) pickupInfo.style.display = 'none';
                            if (deliveryField) deliveryField.setAttribute('required','required');
                            if (deliveryFeeRow) {
                                deliveryFeeRow.classList.remove('d-none');
                                deliveryFeeRow.style.display = '';
                            }
                            if (deliveryFeeAmount) deliveryFeeAmount.textContent = formatCurrencyCLP(DELIVERY_FEE);
                        }
                        
                        updateTotal();
                    }

                    if (deliveryRadio) deliveryRadio.addEventListener('change', updateDeliveryUI);
                    if (pickupRadio) pickupRadio.addEventListener('change', updateDeliveryUI);
                    window.__checkout = window.__checkout || {};
                    window.__checkout.updateTotal = updateTotal;
                    window.__checkout.setDiscountPercentage = function(p) { discountPercentage = p; updateTotal(); };
                    window.__checkout.updateDeliveryUI = updateDeliveryUI;

                    updateDeliveryUI();
                })();

                var IS_FIRST_ORDER = <?php echo (isset($isFirstOrder) && $isFirstOrder) ? 'true' : 'false'; ?>;

                (function(){
                    var emailInput = document.getElementById('customerEmailInput');
                    var eligibilityEl = document.getElementById('emailEligibility');
                    var applyBtn = document.getElementById('applyDiscountBtn');
                    var debounceTimer = null;

                    function setEligibility(eligible) {
                        IS_FIRST_ORDER = !!eligible;
                        if (!eligibilityEl) return;
                        if (eligible) {
                            eligibilityEl.style.display = '';
                            eligibilityEl.className = 'form-text text-success';
                            eligibilityEl.textContent = 'Este email es elegible para WELCOME15 (primer pedido).';
                            if (applyBtn) applyBtn.disabled = false;
                        } else {
                            eligibilityEl.style.display = '';
                            eligibilityEl.className = 'form-text text-danger';
                            eligibilityEl.textContent = 'Este email ya tiene pedidos. WELCOME15 no será aplicable.';
                        }
                    }

                    function checkEmailAjax(email) {
                        if (!email || email.indexOf('@') === -1) {
                            if (eligibilityEl) { eligibilityEl.style.display = 'none'; }
                            return;
                        }

                        fetch('/cart/check-email?email=' + encodeURIComponent(email), { credentials: 'same-origin' })
                            .then(function(res){ return res.json(); })
                            .then(function(data){
                                if (data && data.ok) {
                                    setEligibility(data.eligible);
                                }
                            }).catch(function(){ /* no-op */ });
                    }

                    if (emailInput) {
                        emailInput.addEventListener('input', function(e){
                            var val = e.target.value.trim();
                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(function(){ checkEmailAjax(val); }, 500);
                        });

                        if (emailInput.value) checkEmailAjax(emailInput.value.trim());
                    }
                })();

                function applyDiscount() {
                    var discountCodeInput = document.getElementById('discountCode');
                    var code = discountCodeInput.value.trim().toUpperCase();
                    
                    // Validar códigos de descuento
                    var discountPercentage = 0;
                    var isValid = false;
                    
                    if (code === 'WELCOME15') {
                        // Si el usuario no es elegible, mostrar aviso y no aplicar
                        if (typeof IS_FIRST_ORDER !== 'undefined' && !IS_FIRST_ORDER) {
                            var alertHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                                '<i class="bi bi-exclamation-triangle"></i> El código WELCOME15 solo aplica en tu primer pedido.' +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                                '</div>';
                            discountCodeInput.parentElement.parentElement.insertAdjacentHTML('beforebegin', alertHTML);
                            discountCodeInput.value = '';
                            return;
                        }

                        discountPercentage = 15;
                        isValid = true;
                    } else if (code !== '') {
                        var alertHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                            '<i class="bi bi-exclamation-triangle"></i> Código de descuento inválido' +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>';
                        discountCodeInput.parentElement.parentElement.insertAdjacentHTML('beforebegin', alertHTML);
                        discountCodeInput.value = '';
                        return;
                    }
                    
                    var cartTotalDisplay = document.getElementById('cartTotalDisplay');
                    var productSubtotal = parseFloat(cartTotalDisplay ? cartTotalDisplay.getAttribute('data-subtotal') : 0) || 0;
                    var deliveryRadio = document.getElementById('deliveryTypeDelivery');
                    var pickupRadio = document.getElementById('deliveryTypePickup');
                    var DELIVERY_FEE = 3000;
                    
                    var deliveryFee = (deliveryRadio && deliveryRadio.checked) ? DELIVERY_FEE : 0;
                    var discountAmount = (productSubtotal * discountPercentage) / 100;
                    var total = productSubtotal + deliveryFee - discountAmount;
                    
                    var discountRow = document.getElementById('discountRow');
                    var discountAmountDisplay = document.getElementById('discountAmount');
                    
                    if (discountPercentage > 0) {
                        discountRow.style.display = '';
                        discountAmountDisplay.textContent = '-$' + new Intl.NumberFormat('es-CL', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(discountAmount);
                        cartTotalDisplay.textContent = '$' + new Intl.NumberFormat('es-CL', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(total);
                        
                        var hiddenCode = document.getElementById('discountCodeHidden');
                        var hiddenPerc = document.getElementById('discountPercentageHidden');
                        if (hiddenCode) hiddenCode.value = code;
                        if (hiddenPerc) hiddenPerc.value = discountPercentage;

                        if (window.__checkout && typeof window.__checkout.setDiscountPercentage === 'function') {
                            window.__checkout.setDiscountPercentage(discountPercentage);
                        }
                        
                        var alertHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            '<i class="bi bi-check-circle"></i> <strong>¡Descuento aplicado!</strong> Ahorraste $' +
                            new Intl.NumberFormat('es-CL', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(discountAmount) +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>';
                        discountCodeInput.parentElement.parentElement.insertAdjacentHTML('beforebegin', alertHTML);
                        
                        // Desabilitar el campo después de aplicar
                        discountCodeInput.disabled = true;
                        document.getElementById('applyDiscountBtn').disabled = true;
                    } else {
                        discountRow.style.display = 'none';
                        // Limpiar campos ocultos si no hay descuento
                        var hiddenCode = document.getElementById('discountCodeHidden');
                        var hiddenPerc = document.getElementById('discountPercentageHidden');
                        if (hiddenCode) hiddenCode.value = '';
                        if (hiddenPerc) hiddenPerc.value = 0;

                        // Asegurar que el IIFE también conoce que el descuento quedó en 0
                        if (window.__checkout && typeof window.__checkout.setDiscountPercentage === 'function') {
                            window.__checkout.setDiscountPercentage(0);
                        }
                    }
                }

                function removeDiscount() {
                    // Limpiar estado de descuento en UI y en campos ocultos
                    var discountRow = document.getElementById('discountRow');
                    var discountAmountDisplay = document.getElementById('discountAmount');
                    var cartTotalDisplay = document.getElementById('cartTotalDisplay');
                    var discountCodeInput = document.getElementById('discountCode');
                    var applyBtn = document.getElementById('applyDiscountBtn');

                    // Limpiar valores visibles
                    if (discountAmountDisplay) discountAmountDisplay.textContent = '-$0';
                    if (discountRow) discountRow.style.display = 'none';

                    // Limpiar campos ocultos
                    var hiddenCode = document.getElementById('discountCodeHidden');
                    var hiddenPerc = document.getElementById('discountPercentageHidden');
                    if (hiddenCode) hiddenCode.value = '';
                    if (hiddenPerc) hiddenPerc.value = 0;

                    // Rehabilitar input y botón
                    if (discountCodeInput) { discountCodeInput.disabled = false; discountCodeInput.value = ''; }
                    if (applyBtn) applyBtn.disabled = false;

                    // Eliminar alertas de éxito relacionadas al descuento
                    var alerts = document.querySelectorAll('.alert.alert-success.alert-dismissible');
                    alerts.forEach(function(a){
                        if (a.textContent && a.textContent.indexOf('¡Descuento aplicado!') !== -1) {
                            a.remove();
                        }
                    });

                    // Actualizar la variable interna y recalcular totales
                    if (window.__checkout && typeof window.__checkout.setDiscountPercentage === 'function') {
                        window.__checkout.setDiscountPercentage(0);
                    } else {
                        // fallback: recalcular manualmente
                        var productSubtotal = parseFloat(cartTotalDisplay ? cartTotalDisplay.getAttribute('data-subtotal') : 0) || 0;
                        var deliveryRadio = document.getElementById('deliveryTypeDelivery');
                        var deliveryFee = (deliveryRadio && deliveryRadio.checked) ? 3000 : 0;
                        var total = productSubtotal + deliveryFee;
                        if (cartTotalDisplay) cartTotalDisplay.textContent = '$' + new Intl.NumberFormat('es-CL', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(total);
                    }
                }
                </script>
            
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
                            <span>$<?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
                        </div>
                        <?php endforeach; ?>
                        
                        <hr>
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-tag"></i> Código de Descuento (Opcional)</label>
                            <div class="input-group">
                                <input type="text" id="discountCode" class="form-control" placeholder="Ej: WELCOME15">
                                <button class="btn btn-success" type="button" id="applyDiscountBtn" onclick="applyDiscount()">
                                    <i class="bi bi-check-circle"></i> Aplicar
                                </button>
                            </div>
                            <?php if (!isset($_SESSION['user_email']) || (isset($isFirstOrder) && $isFirstOrder)): ?>
                            <small class="form-text text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> <strong>WELCOME15:</strong> 15% de descuento en tu primer pedido
                            </small>
                            <?php endif; ?>
                            
                        </div>
                        <?php
                            $productTotal = floatval($cartTotal ?? 0);
                            $deliveryFee = 3000;
                            $initiallyDelivery = true;
                            $subtotal = $productTotal;
                            $deliveryToShow = $initiallyDelivery ? $deliveryFee : 0;
                            $initialTotal = $subtotal + $deliveryToShow;
                        ?>

                        <div id="discountRow" class="d-flex justify-content-between mb-2 align-items-center" style="display:none;">
                            <div>
                                <span>Descuento:</span>
                                <span id="discountAmount" class="text-success">-$0</span>
                            </div>
                            <div>
                                <button type="button" id="removeDiscountBtn" class="btn btn-sm btn-outline-secondary" onclick="removeDiscount()">Quitar</button>
                            </div>
                        </div>
                        <div id="deliveryFeeRow" class="d-flex justify-content-between mb-2 <?php echo $initiallyDelivery ? '' : 'd-none'; ?>">
                            <span>Envío:</span>
                            <span id="deliveryFeeAmount" class="text-muted"><?php echo '$' . number_format($initiallyDelivery ? $deliveryFee : 0, 0, ',', '.'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotalDisplay">$<?php echo number_format($initialTotal, 0, ',', '.'); ?></span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="cartTotalDisplay" class="h5 text-success" data-subtotal="<?php echo $subtotal; ?>\"><?php echo '$' . number_format($initialTotal, 0, ',', '.'); ?></strong>
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
    (function(){
        var btn = document.getElementById('btnVerifyAddress');
        var field = document.getElementById('delivery_address');
        var statusEl = document.getElementById('verifyStatus');
        var msgEl = document.getElementById('verifyMessage');
        var mapEl = document.getElementById('addressMap');
        var latEl = document.getElementById('delivery_lat');
        var lngEl = document.getElementById('delivery_lng');
        var normEl = document.getElementById('delivery_address_normalized');
        var map = null, marker = null;

        function setStatus(type, text){
            if (!statusEl) return;
            var classes = {
                success: 'badge bg-success',
                warning: 'badge bg-warning text-dark',
                error: 'badge bg-danger',
                idle: 'badge bg-secondary'
            };
            statusEl.className = classes[type] || classes.idle;
            statusEl.textContent = text;
        }

        function initMap(lat, lng){
            if (!mapEl) return;
            mapEl.style.display = '';
            if (!map) {
                map = L.map('addressMap');
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);
            }
            if (marker) { marker.remove(); }
            marker = L.marker([lat, lng]).addTo(map);
            map.setView([lat, lng], 16);
        }

        function verify(){
            var q = (field && field.value ? field.value.trim() : '');
            if (!q) {
                setStatus('warning','Ingrese una dirección');
                return;
            }
            setStatus('warning','Verificando...');
            msgEl.style.display = 'none';
            fetch('/address/validate?q=' + encodeURIComponent(q), { credentials: 'same-origin'})
                .then(function(r){ return r.json(); })
                .then(function(data){
                    if (data && data.ok && data.result) {
                        var r = data.result;
                        var lat = parseFloat(r.lat), lng = parseFloat(r.lon);
                        latEl.value = isFinite(lat) ? lat : '';
                        lngEl.value = isFinite(lng) ? lng : '';
                        normEl.value = r.display_name || '';
                        setStatus('success','Verificada');
                        if (r.display_name) {
                            msgEl.textContent = 'Dirección estandarizada: ' + r.display_name;
                            msgEl.style.display = '';
                        }
                        if (isFinite(lat) && isFinite(lng)) initMap(lat,lng);
                        updateSubmitButton();
                    } else {
                        setStatus('error','No encontrada');
                        msgEl.textContent = (data && data.message) ? data.message : 'No se pudo verificar';
                        msgEl.style.display = '';
                        latEl.value = '';
                        lngEl.value = '';
                        normEl.value = '';
                        updateSubmitButton();
                    }
                }).catch(function(){
                    setStatus('error','Error');
                    msgEl.textContent = 'Error de conexión al verificar la dirección';
                    msgEl.style.display = '';
                    latEl.value = '';
                    lngEl.value = '';
                    normEl.value = '';
                    updateSubmitButton();
                });
        }
        
        function updateSubmitButton(){
            var submitBtn = document.getElementById('submitOrderBtn');
            var deliveryRadio = document.getElementById('deliveryTypeDelivery');
            var pickupRadio = document.getElementById('deliveryTypePickup');
            if (!submitBtn) return;
            
            var isDelivery = deliveryRadio && deliveryRadio.checked;
            var isPickup = pickupRadio && pickupRadio.checked;
            
            if (isPickup) {
                submitBtn.disabled = false;
                submitBtn.title = '';
            } else if (isDelivery) {
                var lat = latEl.value;
                var lng = lngEl.value;
                var verified = (lat && lng && lat !== '' && lng !== '');
                submitBtn.disabled = !verified;
                if (!verified) {
                    submitBtn.title = 'Debe verificar la dirección de entrega antes de continuar';
                } else {
                    submitBtn.title = '';
                }
            }
        }
        
        if (btn) btn.addEventListener('click', verify);
        
        var deliveryRadio = document.getElementById('deliveryTypeDelivery');
        var pickupRadio = document.getElementById('deliveryTypePickup');
        if (deliveryRadio) deliveryRadio.addEventListener('change', updateSubmitButton);
        if (pickupRadio) pickupRadio.addEventListener('change', updateSubmitButton);
        
        updateSubmitButton();
    })();
    </script>
</body>
</html>