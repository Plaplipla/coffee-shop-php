<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    <div class="container mt-4">
        <h1>Agregar Producto</h1>
        <form method="POST" action="/products/store">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <input class="form-control" name="category">
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input class="form-control" name="price" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input class="form-control" name="stock" value="0">
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen (ruta)</label>
                <input class="form-control" name="image">
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="description"></textarea>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_new" id="is_new">
                <label class="form-check-label" for="is_new">Marcar como "Nuevo"</label>
            </div>
            <button class="btn btn-coffee">Crear</button>
        </form>
    </div>
</body>
</html>