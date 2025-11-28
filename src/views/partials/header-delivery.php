<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entregas - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="/home">
                <img class="logo" src="../../images/logo.png" alt="Cafetería Aroma">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDeliveryNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarDeliveryNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/home">
                            <i class="bi bi-house-fill m-1"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/delivery/orders">
                            <i class="bi bi-truck m-1"></i> Mis Entregas
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item m-2">
                        <span class="user-badge">
                            <i class="bi bi-person-circle m-1"></i> 
                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuario'); ?> 
                            <small>(<?php echo ucfirst($_SESSION['user_role'] ?? 'repartidor'); ?>)</small>
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
