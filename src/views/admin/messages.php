<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes de Contacto - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>
    
    <div class="container mt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-envelope-fill"></i> Mensajes de Contacto</h1>
            <a href="/admin/dashboard" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($allMessages)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No hay mensajes de contacto todavía.
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <?php foreach ($allMessages as $msg): 
                        $msgObj = is_array($msg) ? (object)$msg : $msg;
                        $isRead = !empty($msgObj->leido);
                        $msgId = is_object($msgObj->_id) ? (string)$msgObj->_id : $msgObj->_id;
                        $fecha = isset($msgObj->fecha) ? $msgObj->fecha->toDateTime()->format('d/m/Y H:i') : 'N/A';
                    ?>
                        <div class="message-card p-4 border-bottom" style="background: <?php echo $isRead ? '#f8f9fa' : 'white'; ?>;">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-2">
                                        <h5 class="mb-0 me-3" style="color: var(--coffee-dark);">
                                            <i class="bi bi-person-circle me-2"></i>
                                            <?php echo htmlspecialchars($msgObj->nombre ?? 'N/A'); ?>
                                        </h5>
                                        <?php if (!$isRead): ?>
                                            <span class="badge bg-warning text-dark">Nuevo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Leído</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-envelope me-2"></i>
                                        <a href="mailto:<?php echo htmlspecialchars($msgObj->email ?? ''); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($msgObj->email ?? 'N/A'); ?>
                                        </a>
                                    </p>
                                    
                                    <div class="message-content mt-3 p-3" style="background: white; border-left: 4px solid var(--coffee-brown); border-radius: 5px;">
                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($msgObj->mensaje ?? 'Sin mensaje')); ?></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 text-end">
                                    <p class="text-muted mb-3">
                                        <i class="bi bi-calendar3 me-2"></i>
                                        <?php echo $fecha; ?>
                                    </p>
                                    
                                    <?php if (!$isRead): ?>
                                        <a href="/admin/mark-message-read?id=<?php echo urlencode($msgId); ?>" 
                                           class="btn btn-sm btn-success">
                                            <i class="bi bi-check2"></i> Marcar como leído
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
