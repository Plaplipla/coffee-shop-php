<?php
$title = 'Verificación exitosa';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="3;url=/login" />
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h1 class="h4 mb-3">¡Email verificado!</h1>
                    <p class="mb-4">Tu cuenta ha sido activada correctamente. Serás redirigido al inicio de sesión en unos segundos.</p>
                    <a href="/login" class="btn btn-primary">Ir al inicio de sesión ahora</a>
                </div>
            </div>
            <p class="text-center text-muted mt-3" style="font-size: 0.9rem;">Por seguridad, esta página expira en pocos minutos.</p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>