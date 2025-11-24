<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos - Aroma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <div class="container mt-4">
        <div class="d-flex align-items-center justify-content-between m-2">
            <h1>Gestionar Productos</h1>
            <div>
                <a href="/products/create" class="btn btn-coffee m-1">Agregar Producto</a>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <div class="alert alert-info">No hay productos disponibles.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p):
                            $name = is_array($p) ? ($p['name'] ?? '') : ($p->name ?? '');
                            $price = is_array($p) ? ($p['price'] ?? 0) : ($p->price ?? 0);
                            $stock = is_array($p) ? ($p['stock'] ?? 0) : ($p->stock ?? 0);
                            $id = is_array($p) ? ($p['_id'] ?? '') : ($p->_id ?? '');
                            $image = is_array($p) ? ($p['image'] ?? '') : ($p->image ?? '');
                            $active = is_array($p) ? (!empty($p['active'])) : (!empty($p->active));
                        ?>
                        <tr style="<?php echo !$active ? 'opacity: 0.5;' : ''; ?>">
                            <td style="width:80px;">
                                <?php if ($image): ?>
                                    <img src="<?php echo htmlspecialchars($image); ?>" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($name); ?></td>
                            <td><?php echo htmlspecialchars(is_array($p) ? ($p['category'] ?? '') : ($p->category ?? '')); ?></td>
                            <td>$<?php echo number_format(floatval($price),2); ?></td>
                            <td><?php echo intval($stock); ?></td>
                            <td>
                                <?php if ($active): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/products/edit?id=<?php echo urlencode((string)$id); ?>" class="btn btn-sm btn-primary">Editar</a>
                                <?php if ($active): ?>
                                    <a href="/products/toggle-status?id=<?php echo urlencode((string)$id); ?>" class="btn btn-sm btn-warning">Desactivar</a>
                                <?php else: ?>
                                    <a href="/products/toggle-status?id=<?php echo urlencode((string)$id); ?>" class="btn btn-sm btn-success">Activar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>