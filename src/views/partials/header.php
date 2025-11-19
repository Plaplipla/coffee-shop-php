<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<?php //Navbar segun rol
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'administrador') {
    // administrador
    include __DIR__ . '/header-admin.php';
} elseif (isset($_SESSION['user_id']) && in_array($_SESSION['user_role'] ?? null, ['empleado', 'trabajador'])) {
    // empleado/trabajador
    include __DIR__ . '/header-employee.php';
} else {
    // clientes
?>

<header>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <div id="discount-aviso" class="aviso text-white text-center py-2 position-relative">
            <p class="mb-0">¡Disfruta de un 15% de descuento en tu primera compra al registrarte!</p>
            <button id="discount-close" type="button" aria-label="Cerrar aviso" style="position:absolute;right:10px;top:6px;background:transparent;border:none;color:#fff;font-size:1.1rem;line-height:1;cursor:pointer;">&times;</button>
        </div>
        <script>
        document.getElementById('discount-close').addEventListener('click', function () {
            document.getElementById('discount-aviso').style.display = 'none';
        });
        </script>
    <?php endif; ?>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="/home">
                <img class="logo" src="../../images/logo.png" alt="Cafetería Aroma">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/home">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/menu">Menú</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/track-order" style="color: #d4af37; font-weight: 600;">
                            <i class="bi bi-box-seam m-1"></i> Seguir mi pedido
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative" href="/cart">
                            <i class="bi bi-cart3" style="font-size: 1.2rem; color: white"></i> Carrito
                            <?php 
                            $cartCount = 0;
                            if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $item) {
                                    $cartCount += $item['quantity'];
                                }
                            }
                            if ($cartCount > 0): ?>
                            <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cartCount; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                         <li class="nav-item m-2">
                            <span class="user-badge">
                                <i class="bi bi-person-circle m-1"></i> 
                                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?> 
                                <small>(<?php echo ucfirst($_SESSION['user_role'] ?? 'user'); ?>)</small>
                            </span>
                        </li>
                        <li class="nav-item m-2">
                            <a href="/logout" class="btn btn-logout">
                                <i class="bi bi-box-arrow-right"></i> Salir
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item d-flex flex-column align-items-center">
                            <a class="btn user-badge mb-2" href="/register">Regístrate aquí</a>
                            <small class="text-white">
                                ¿Ya tienes cuenta? <a href="/login" style="color: #f5deb3;">Inicia sesión</a>
                            </small>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<?php }
?>
