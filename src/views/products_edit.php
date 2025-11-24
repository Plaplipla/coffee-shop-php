<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    <div class="container mt-4">
        <h1>Editar Producto</h1>
        <?php
            $p = $product;
            $id = is_object($p) ? (string)$p->_id : ($p['_id'] ?? '');
            $name = is_object($p) ? ($p->name ?? '') : ($p['name'] ?? '');
            $category = is_object($p) ? ($p->category ?? '') : ($p['category'] ?? '');
            $price = is_object($p) ? ($p->price ?? 0) : ($p['price'] ?? 0);
            $stock = is_object($p) ? ($p->stock ?? 0) : ($p['stock'] ?? 0);
            $image = is_object($p) ? ($p->image ?? '') : ($p['image'] ?? '');
            $description = is_object($p) ? ($p->description ?? '') : ($p['description'] ?? '');
            $is_new = is_object($p) ? (!empty($p->is_new)) : (!empty($p['is_new']));
        ?>
        <form method="POST" action="/products/update" class="mb-3">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoría <span class="text-danger">*</span></label>
                <select class="form-control" name="category" required>
                    <option value="">-- Selecciona una categoría --</option>
                    <?php if (isset($categories)): ?>
                        <?php foreach ($categories as $key => $label): ?>
                            <option value="<?php echo htmlspecialchars($key); ?>" 
                                <?php echo ($category === $key) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($label); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="form-text text-muted">Selecciona una categoría de la lista de categorías disponibles</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input class="form-control" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input class="form-control" name="stock" value="<?php echo htmlspecialchars($stock); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen (URL)</label>
                <input class="form-control" type="url" name="image" value="<?php echo htmlspecialchars($image); ?>" placeholder="https://i.postimg.cc/...">
                <small class="form-text text-muted">URL completa de la imagen. Ejemplo: https://i.postimg.cc/...</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="description"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_new" id="is_new" <?php echo $is_new ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_new">Marcar como "Nuevo"</label>
            </div>
            <button class="btn btn-coffee">Actualizar</button>
        </form>
    </div>
</body>
</html>