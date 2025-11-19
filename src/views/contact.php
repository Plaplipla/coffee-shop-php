<?php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Cafetería Aroma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    
    <main class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-4 pb-3">Contacto</h1>
            <div class="row">
                <div class="col-md-6">
                    <h3>Información de Contacto</h3>
                    <p><strong>Dirección:</strong> Av. Principal 123, Santiago</p>
                    <p><strong>Teléfono:</strong> +56 2 2345 6789</p>
                    <p><strong>Email:</strong> info@cafeteriaaroma.cl</p>
                    <p><strong>Horario:</strong> Lunes a Domingo 7:00 - 22:00 hrs</p>
                </div>
                <div class="col-md-6">
                    <h3>Envíanos un Mensaje</h3>
                    <form>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensaje" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-coffee">Enviar Mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>