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
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <main class="container my-5">
        <h1 class="text-center mb-5" style="color: var(--coffee-dark); font-weight: bold;">Contacto</h1>
        
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #3d2817 0%, #5c3d2e 100%);">
                    <div class="card-body p-4 text-white">
                        <h3 class="mb-4" style="border-bottom: 3px solid #D4A574; display: inline-block; padding-bottom: 10px;">
                            <i class="bi bi-info-circle-fill me-2"></i>Información de Contacto
                        </h3>
                        
                        <div class="contact-info-item mb-4 p-3" style="background: rgba(255,255,255,0.1); border-radius: 10px; border-left: 4px solid #D4A574;">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-geo-alt-fill me-3" style="font-size: 1.5rem; color: #D4A574;"></i>
                                <div>
                                    <strong style="color: #D4A574; font-size: 0.9rem;">Dirección:</strong>
                                    <p class="mb-0 mt-1">Av. Principal 123, Santiago</p>
                                </div>
                            </div>
                        </div>

                        <div class="contact-info-item mb-4 p-3" style="background: rgba(255,255,255,0.1); border-radius: 10px; border-left: 4px solid #D4A574;">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-telephone-fill me-3" style="font-size: 1.5rem; color: #D4A574;"></i>
                                <div>
                                    <strong style="color: #D4A574; font-size: 0.9rem;">Teléfono:</strong>
                                    <p class="mb-0 mt-1">+56 2 2345 6789</p>
                                </div>
                            </div>
                        </div>

                        <div class="contact-info-item mb-4 p-3" style="background: rgba(255,255,255,0.1); border-radius: 10px; border-left: 4px solid #D4A574;">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-envelope-fill me-3" style="font-size: 1.5rem; color: #D4A574;"></i>
                                <div>
                                    <strong style="color: #D4A574; font-size: 0.9rem;">Email:</strong>
                                    <p class="mb-0 mt-1">info@cafeteriaaroma.cl</p>
                                </div>
                            </div>
                        </div>

                        <div class="contact-info-item mb-0 p-3" style="background: rgba(255,255,255,0.1); border-radius: 10px; border-left: 4px solid #D4A574;">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-clock-fill me-3" style="font-size: 1.5rem; color: #D4A574;"></i>
                                <div>
                                    <strong style="color: #D4A574; font-size: 0.9rem;">Horario:</strong>
                                    <p class="mb-0 mt-1">Lunes a Domingo 7:00 - 22:00 hrs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-4" style="background: linear-gradient(135deg, #ECE0D1 0%, #DED0BF 100%);">
                        <h3 class="mb-4" style="color: var(--coffee-dark); border-bottom: 3px solid var(--coffee-brown); display: inline-block; padding-bottom: 10px;">
                            <i class="bi bi-chat-dots-fill me-2"></i>Envíanos un Mensaje
                        </h3>
                        
                        <?php if (isset($_SESSION['contact_success'])): ?>
                            <div class="alert alert-light border-start border-4 border-success alert-dismissible fade show" role="alert" id="contactSuccessAlert">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> 
                                <?php echo $_SESSION['contact_success']; unset($_SESSION['contact_success']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['contact_error'])): ?>
                            <div class="alert alert-light border-start border-4 border-danger alert-dismissible fade show" role="alert" id="contactErrorAlert">
                                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> 
                                <?php echo $_SESSION['contact_error']; unset($_SESSION['contact_error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="/contact/send">
                            <div class="mb-4">
                                <label for="nombre" class="form-label fw-bold" style="color: var(--coffee-dark);">
                                    <i class="bi bi-person me-2"></i>Nombre
                                </label>
                                <input type="text" name="nombre" class="form-control form-control-lg" id="nombre" required 
                                       style="border: 2px solid var(--coffee-brown); border-radius: 10px; background: white;"
                                       placeholder="Tu nombre completo">
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold" style="color: var(--coffee-dark);">
                                    <i class="bi bi-envelope me-2"></i>Email
                                </label>
                                <input type="email" name="email" class="form-control form-control-lg" id="email" required
                                       style="border: 2px solid var(--coffee-brown); border-radius: 10px; background: white;"
                                       placeholder="tu@email.com">
                            </div>
                            
                            <div class="mb-4">
                                <label for="mensaje" class="form-label fw-bold" style="color: var(--coffee-dark);">
                                    <i class="bi bi-chat-text me-2"></i>Mensaje
                                </label>
                                <textarea name="mensaje" class="form-control form-control-lg" id="mensaje" rows="6" required
                                          style="border: 2px solid var(--coffee-brown); border-radius: 10px; background: white;"
                                          placeholder="Escribe tu mensaje aquí..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-coffee btn-lg w-100" 
                                    style="border-radius: 10px; padding: 15px; font-weight: bold; font-size: 1.1rem;">
                                <i class="bi bi-send-fill me-2"></i>Enviar Mensaje
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include __DIR__ . '/partials/footer.php'; ?>
    <script>
        // Auto-cerrar alertas después de 5 segundos
        setTimeout(function() {
            const successAlert = document.getElementById('contactSuccessAlert');
            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }
            
            const errorAlert = document.getElementById('contactErrorAlert');
            if (errorAlert) {
                errorAlert.style.transition = 'opacity 0.5s';
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 500);
            }
        }, 5000);
    </script>