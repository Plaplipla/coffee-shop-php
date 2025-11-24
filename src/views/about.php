<?php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - Cafetería Aroma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <section class="py-5" style="background: linear-gradient(rgba(36, 36, 36, 0.7), rgba(36, 36, 36, 0.7)), url('/images/local.png') center/cover no-repeat;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="display-3 text-white mb-4 ">Sobre Nosotros</h1>
                        <p class="lead text-white" style="font-size: 1.2rem; line-height: 1.8; max-width: 700px;">Nacimos con una idea simple: el ritual de tomar café sabe mejor cuando se comparte. Nos diferenciamos por ser un espacio-servicio cálido, productos locales y recetas artesanales que priorizan la calidad y el buen trato. Nuestra misión es ofrecer una experiencia memorable en cada visita.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <h2 class="mb-3 text-coffee">Nuestra Misión</h2>
                        <p>En Cafetería Aroma queremos ofrecerte más que una taza de café: buscamos que el café despierte los sentidos en un entorno auténtico, local. Nuestro café es tostado en la región y es de alta calidad. Apostamos por lo fresco, lo artesanal y el servicio profesional.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <span class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </span>
                                Calidad premium
                            </li>
                            <li class="mb-2">
                                <span class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </span>
                                Productos locales
                            </li>
                            <li class="mb-2">
                                <span class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </span>
                                Recetas artesanales
                            </li>
                            <li class="mb-2">
                                <span class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </span>
                                Excelente servicio
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <h2 class="mb-3 text-coffee">Nuestra Visión</h2>
                        <p>Ser la cafetería de referencia en la ciudad, reconocida por la calidad de nuestro café y nuestro compromiso de entregar experiencias acogedoras. Queremos inspirar a más personas a vivir el ritual del café a su propio ritmo, junto a amistades auténticas en torno al sabor de cada infusión.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <span class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-star-fill text-warning"></i>
                                </span>
                                Referente de calidad
                            </li>
                            <li class="mb-2">
                                <span class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-star-fill text-warning"></i>
                                </span>
                                Experiencias memorables
                            </li>
                            <li class="mb-2">
                                <span class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-star-fill text-warning"></i>
                                </span>
                                Comunidad auténtica
                            </li>
                            <li class="mb-2">
                                <span class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle me-2" style="width: 32px; height: 32px;">
                                    <i class="bi bi-star-fill text-warning"></i>
                                </span>
                                Inspiración diaria
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="mb-3">¡Ven a vivir la experiencia Aroma!</h2>
                    <p class="lead">Nada se compara con disfrutar el aroma del café recién molido en nuestra cafetería.<br>Te esperamos con ambiente cálido, buena música y sabores que despiertan sonrisas.</p>
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="bi bi-geo-alt-fill text-coffee me-2"></i>Ubicación</h5>
                                <p><strong>Av. Los Aromos 123, Melipilla</strong></p>
                                <p class="text-muted">Región Metropolitana, Chile</p>
                                <a href="https://maps.google.com" target="_blank" class="btn btn-sm btn-coffee">Ver en mapa</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="bi bi-clock-fill text-coffee me-2"></i>Horario de Atención</h5>
                                <p><strong>Lunes a Viernes:</strong> 08:00 - 20:00</p>
                                <p><strong>Sábado y Domingo:</strong> 09:00 - 19:00</p>
                                <p class="text-muted small">¡Abierto todos los días para ti!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 rounded-3" style="background-color: #d1ecf1; border-left: 4px solid #0dcaf0; color: #055160;">
                    <i class="bi bi-gift-fill me-2"></i><strong>Promoción exclusiva:</strong> Menciona <em>"Coffee Time"</em> en nuestras redes sociales, etiquétanos y obtén un <strong>10% de descuento</strong> en tu bebida favorita.
                </div>
            </div>
        </section>

        <section class="py-5" style="background: linear-gradient(135deg, #ECE0D1 0%, #D4A574 100%);">
            <div class="container">
                <h2 class="text-center mb-5" style="color: var(--coffee-dark);">Nuestros Valores</h2>
                <div class="row text-center">
                    <div class="col-md-3 mb-4">
                        <div class="feature-box">
                            <i class="bi bi-heart-fill" style="font-size: 2.5rem; color: var(--coffee-brown);"></i>
                            <h5 class="mt-3 mb-2" style="color: var(--coffee-dark);">Pasión</h5>
                            <p style="color: var(--coffee-dark);">Por el café y por nuestros clientes</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="feature-box">
                            <i class="bi bi-star-fill" style="font-size: 2.5rem; color: var(--coffee-brown);"></i>
                            <h5 class="mt-3 mb-2" style="color: var(--coffee-dark);">Calidad</h5>
                            <p style="color: var(--coffee-dark);">En cada detalle de nuestra experiencia</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="feature-box">
                            <i class="bi bi-people-fill" style="font-size: 2.5rem; color: var(--coffee-brown);"></i>
                            <h5 class="mt-3 mb-2" style="color: var(--coffee-dark);">Comunidad</h5>
                            <p style="color: var(--coffee-dark);">Construyendo lazos auténticos</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="feature-box">
                            <i class="bi bi-flower2" style="font-size: 2.5rem; color: var(--coffee-brown);"></i>
                            <h5 class="mt-3 mb-2" style="color: var(--coffee-dark);">Sostenibilidad</h5>
                            <p style="color: var(--coffee-dark);">Responsables con el planeta</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <style>
        footer.bg-dark {
            margin: 0 !important;
        }
    </style>

    <?php include __DIR__ . '/partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>