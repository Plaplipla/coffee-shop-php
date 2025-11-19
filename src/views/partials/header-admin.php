<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="/home">
                <img class="logo" src="../../images/logo.png" alt="Cafetería Aroma">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdminNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarAdminNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">
                            <i class="bi bi-graph-up m-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/reports">
                            <i class="bi bi-file-earmark-bar-graph m-1"></i> Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employee/orders">
                            <i class="bi bi-lightning-fill m-1"></i> Pedidos Activos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/products">
                            <i class="bi bi-box-seam-fill m-1"></i> Gestionar Productos
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item m-2">
                        <span class="user-badge">
                            <i class="bi bi-person-circle"></i> 
                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?> 
                            <small>(<?php echo ucfirst($_SESSION['user_role'] ?? 'admin'); ?>)</small>
                        </span>
                    </li>
                    <li class="nav-item m-2">
                        <a href="/logout" class="btn btn-logout">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
