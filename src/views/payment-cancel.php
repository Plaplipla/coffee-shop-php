<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Cancelado - Coffee Shop</title>
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
                            <i class="bi bi-x-circle-fill text-warning" style="font-size: 5rem;"></i>
                        </div>
                        
                        <h1 class="display-4 mb-3">Pago Cancelado</h1>
                        <p class="lead text-muted mb-4">
                            Has cancelado el proceso de pago
                        </p>
                        
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle"></i> 
                            <strong>No te preocupes</strong><br>
                            Tu carrito sigue disponible y no se ha realizado ningún cargo a tu tarjeta.
                        </div>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="bi bi-question-circle"></i> ¿Qué puedes hacer ahora?</h5>
                                
                                <ul class="list-unstyled text-start">
                                    <li class="mb-2">
                                        <i class="bi bi-check text-success"></i> 
                                        Volver al carrito y revisar tu pedido
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-success"></i> 
                                        Intentar el pago nuevamente con Stripe
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-success"></i> 
                                        Elegir otro método de pago (tarjeta al recibir o efectivo)
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check text-success"></i> 
                                        Contactarnos si tienes algún problema
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-block">
                            <a href="/cart" class="btn btn-primary btn-lg">
                                <i class="bi bi-cart"></i> Volver al Carrito
                            </a>
                            <a href="/checkout" class="btn btn-success btn-lg">
                                <i class="bi bi-arrow-repeat"></i> Intentar de Nuevo
                            </a>
                            <a href="/home" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-house"></i> Ir al Inicio
                            </a>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-muted">
                                ¿Necesitas ayuda? 
                                <a href="/contact" class="text-decoration-none">
                                    <i class="bi bi-envelope"></i> Contáctanos
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-muted">
                        <i class="bi bi-shield-check"></i> 
                        Tus pagos están protegidos por Stripe
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
