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
        <form method="POST" action="/products/store" novalidate class="mb-3">
            <div class="mb-3">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="name" required minlength="3" maxlength="100" 
                    placeholder="Ej: Latte Cremoso">
                <small class="form-text text-muted">Mínimo 3 caracteres, máximo 100</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoría <span class="text-danger">*</span></label>
                <select class="form-control" name="category" required>
                    <option value="">-- Selecciona una categoría --</option>
                    <?php if (isset($categories)): ?>
                        <?php foreach ($categories as $key => $label): ?>
                            <option value="<?php echo htmlspecialchars($key); ?>">
                                <?php echo htmlspecialchars($label); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="form-text text-muted">Selecciona una categoría de la lista</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio (CLP) <span class="text-danger">*</span></label>
                <input class="form-control" type="number" name="price" required min="1" max="999999"
                    placeholder="Ej: 3500" step="1">
                <small class="form-text text-muted">Solo números. Ejemplo: 3500, 5000, 12500</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock <span class="text-danger">*</span></label>
                <input class="form-control" type="number" name="stock" required min="0" max="9999" 
                    value="0" step="1">
                <small class="form-text text-muted">Solo números. Mínimo 0</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen (URL) <span class="text-danger">*</span></label>
                <input class="form-control" type="url" name="image" required minlength="5" maxlength="255"
                    placeholder="Ej: https://i.postimg.cc/abc123/latte.png">
                <small class="form-text text-muted">URL completa de la imagen. Ejemplo: https://i.postimg.cc/...</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción <span class="text-danger">*</span></label>
                <textarea class="form-control" name="description" required minlength="10" maxlength="500" 
                    rows="4" placeholder="Descripción del producto..."></textarea>
                <small class="form-text text-muted">Mínimo 10 caracteres, máximo 500</small>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_new" id="is_new">
                <label class="form-check-label" for="is_new">Marcar como "Nuevo"</label>
            </div>
            <button class="btn btn-coffee" type="submit">Crear</button>
        </form>
    </div>
</body>
</html>