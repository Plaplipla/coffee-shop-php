<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafetería Aroma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <main>
        <section class="bienvenida py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="bienvenida-columna imagenes text-center">
                            <img class="bienvenida-img img-fluid" src="images/letrero.png" alt="letrero Cafetería Aroma">
                            <img class="bienvenida-img img-fluid" src="images/local.png" alt="Local Cafetería Aroma">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bienvenida-columna texto">
                            <h1 class="display-4 mb-4">¡Bienvenido/a a Cafetería Aroma!</h1>
                            <p class="lead mb-3">En Cafetería Aroma cada taza cuenta una historia. El aroma a café recién molido, la calidez de un espacio pensado para ti y el sabor de lo artesanal son nuestra forma de acompañarte en tus mejores momentos.</p>
                            <p class="mb-4">Ya sea que vengas por tu espresso de todos los días, una pausa con algo dulce o una tarde entre amigos, aquí siempre encontrarás una experiencia hecha a tu medida.</p>
                            <div class="d-flex gap-3">
                                <a href="/menu" class="btn btn-coffee btn-lg">Explora nuestro menú</a>
                                <a href="/track-order" class="btn btn-outline-coffee btn-lg">
                                    <i class="bi bi-box-seam"></i> Seguir mi pedido
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="menu py-5 bg-light">
            <div class="container">
                <h2 class="text-center mb-3">Nuestro Menú</h2>
                <p class="text-center mb-5 lead">¿Listo para el primer sorbo? Tenemos algo para todos los paladares.</p>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card-menu card text-center h-100">
                            <div class="card-body">
                                <img class="img-fluid mb-3" src="images/bebidas.png" alt="Bebidas de café" style="width: 200px; height: 140px; object-fit: cover; border-radius: 10px;">
                                <h4 class="card-title">Bebidas Frías/Calientes</h4>
                                <p class="card-text small text-muted">Desde espressos intensos hasta bebidas heladas refrescantes, todas preparadas con nuestro café de especialidad.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card-menu card text-center h-100">
                            <div class="card-body">
                                <img class="img-fluid mb-3" src="images/pastelería.jpg" alt="Postres" style="width: 200px; height: 140px; object-fit: cover; border-radius: 10px;">
                                <h4 class="card-title">Pastelería y Dulces</h4>
                                <p class="card-text small text-muted">Deliciosos postres artesanales y dulces frescos que van perfectos con tu café favorito.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card-menu card text-center h-100">
                            <div class="card-body">
                                <img class="img-fluid mb-3" src="images/opciones_saladas.jpg" alt="Opciones saladas" style="width: 200px; height: 140px; object-fit: cover; border-radius: 10px;">
                                <h4 class="card-title">Opciones Saladas</h4>
                                <p class="card-text small text-muted">Sándwiches, ensaladas y snacks salados para acompañar tu pausa en la cafetería.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="best-seller py-5">
            <div class="container">
                <h2 class="text-center mb-3">Lo más vendido</h2>
                <p class="text-center mb-5 lead">Descubre nuestros favoritos de la casa, los que siempre vuelven por más.</p>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card-best-seller card h-100 p-3">
                            <img class="card-img-top" src="images/Latte_Cremoso.jpg" alt="Latte Cremoso" style="height: 250px; object-fit: cover; border-radius: 10px;">
                            <div class="card-body" style="display: grid">
                                <h4 class="card-title">Latte Cremoso</h4>
                                <p class="card-text">La estrella de la casa. Café suave y balanceado con leche vaporizada, coronado con una ligera espuma. Perfecto para quienes aman un café delicado pero con carácter.</p>
                                <a href="/menu" class="btn btn-coffee">Pedir ahora</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card-best-seller card h-100 p-3">
                            <img class="card-img-top" src="images/products/brownie.jpg" alt="brownie de chocolate" style="height: 250px; object-fit: cover; border-radius: 10px;">
                            <div class="card-body" style="display: grid">
                                <h4 class="card-title">Brownie Casero</h4>
                                <p class="card-text">Chocolate intenso, textura húmeda y ese toque de nueces que lo hace irresistible. Ideal para acompañar tu café o para darte un gusto sin culpa, pide opción sin azúcar.</p>
                                <a href="/menu" class="btn btn-coffee">Pedir ahora</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card-best-seller card h-100 p-3">
                            <img class="card-img-top" src="images/products/Croissant.jpg" alt="Croissant de mantequilla" style="height: 250px; object-fit: cover; border-radius: 10px;">
                            <div class="card-body" style="display: grid">
                                <h4 class="card-title">Croissant de Mantequilla</h4>
                                <p class="card-text">Clásico, dorado y hojaldrado. Hecho con mantequilla de verdad y horneado cada mañana para mantener la frescura que se siente en cada mordisco.</p>
                                <a href="/menu" class="btn btn-coffee">Pedir ahora</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-us py-5 bg-light">
            <div class="container">
                <h2>Nuestra pasión por el Café</h2>
                <p>En Cafetería Aroma, la calidad es nuestra promesa. Trabajamos directamente con pequeños productores de las mejores regiones cafetaleras del mundo para traerte granos 100% arábica, tostados de forma artesanal para resaltar sus notas únicas: desde el dulzos afrutado de Etiopía hasta el cuerpo achocolatado de Colombia.</p>

                <div class="row text-center align-items-start justify-content-center gx-5">    
                    <div class="col-auto m-4">
                        <div class="img_nuestro_cafe">
                            <img class="imagen-cafeteria img-fluid rounded-circle" src="images/especialidad.png" alt="Café de especialidad">
                            <p class="mt-3">Café de especialidad</p>
                        </div>
                    </div>
                    <div class="col-auto m-4">
                        <div class="img_nuestro_cafe">
                            <img class="imagen-cafeteria img-fluid rounded-circle" src="images/tostado.jpg" alt="Granos de café tostados">
                            <p class="mt-3">Tostado Artesanal</p>
                        </div>
                    </div>
                    <div class="col-auto m-4">
                        <div class="img_nuestro_cafe">
                            <img class="imagen-cafeteria img-fluid rounded-circle" src="images/barista.jpg" alt="Barista preparando café">
                            <p class="mt-3">Expertos Baristas</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="promociones pt-5">
            <div class="container">
                <div class="promociones-texto">
                    <h2>Promociones del Mes</h2>
                    <p>En Cafetería Aroma creemos que un buen café siempre es motivo de celebración… y si viene con descuento, ¡mucho mejor! Este mes preparamos ofertas especiales para que disfrutes más de lo que te gusta, gastando menos.</p>
                    <p><strong>*Tip secreto*</strong> Nuestras promociones cambian cada mes, así que no te duermas... ¡o tu combo favorito podría desaparecer!</p>
                </div>

                <div class="best-seller-cards">
                    <div class="card-best-seller">
                        <img class="img-best-seller" src="images/hora_feliz.png" alt="Hora Feliz">
                        <h4>Happy Hour</h4>
                        <p>Café frío todos los Viernes de 16:00 a 18:00 hrs.</p>
                    </div>
                    <div class="card-best-seller">
                        <img class="img-best-seller" src="images/Latte_Cremoso.jpg" alt="Promo 2x1 Latte">
                        <h4>2x1 en Latte</h4>
                        <p>Disponible día Martes de 10:00 a 12:00 hrs.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>