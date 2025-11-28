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
    <?php elseif (isset($_SESSION['user_id']) && (!isset($_SESSION['email_verified']) || $_SESSION['email_verified'] === false)): ?>
        <div id="email-verification-banner" class="bg-warning text-dark text-center py-2 position-relative">
            <p class="mb-0">
                <i class="bi bi-envelope"></i> 
                <strong>Verifica tu correo electrónico</strong> para acceder a todas las funciones. 
                <a href="#" id="resend-verification" class="text-dark" style="text-decoration: underline;">Reenviar correo</a>
            </p>
            <button id="verification-close" type="button" aria-label="Cerrar aviso" style="position:absolute;right:10px;top:6px;background:transparent;border:none;color:#000;font-size:1.1rem;line-height:1;cursor:pointer;">&times;</button>
        </div>
        <script>
        document.getElementById('verification-close').addEventListener('click', function () {
            document.getElementById('email-verification-banner').style.display = 'none';
        });
        document.getElementById('resend-verification').addEventListener('click', async function (e) {
            e.preventDefault();
            const response = await fetch('/auth/resend-verification');
            const data = await response.json();
            alert(data.message);
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
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Cliente no autenticado: Carrito normal -->
                    <?php if (!isset($_SESSION['user_id'])): ?>
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
                        <li class="nav-item d-flex flex-column align-items-center">
                            <a class="btn user-badge mb-2" href="/register">Regístrate aquí</a>
                            <small class="text-white">
                                ¿Ya tienes cuenta? <a href="/login" style="color: #f5deb3;">Inicia sesión</a>
                            </small>
                        </li>
                    <?php elseif ($_SESSION['user_role'] === 'cliente'): ?>
                        <li class="nav-item dropdown">
                            <button class="nav-link dropdown-toggle user-badge" 
                                    id="clientDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                    style="border: none; cursor: pointer;">
                                <i class="bi bi-person-circle p-1"></i> 
                                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?>
                                <small class="m-1">(Cliente)</small>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="clientDropdown">
                                <li>
                                    <a class="dropdown-item" href="/cart">
                                        <i class="bi bi-cart3"></i> 
                                        Carrito
                                        <?php 
                                        $cartCount = 0;
                                        if (isset($_SESSION['cart'])) {
                                            foreach ($_SESSION['cart'] as $item) {
                                                $cartCount += $item['quantity'];
                                            }
                                        }
                                        if ($cartCount > 0): ?>
                                            <span class="badge bg-danger ms-2"><?php echo $cartCount; ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="/track-order">
                                        <i class="bi bi-box-seam"></i> Seguir mi pedido
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/order-history">
                                        <i class="bi bi-receipt"></i> Historial de pedidos
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="/logout">
                                        <i class="bi bi-box-arrow-right"></i> Salir
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Otros usuarios autenticados (empleado, etc) -->
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
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<?php }
?>
