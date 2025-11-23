<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú - Cafetería Aroma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/menu.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <main>
        <div class="container my-5">
            <!-- Filtros por categoría -->
            <div class="category-filters">
                <div class="filter-container">
                    <button class="filter-btn" data-filter="cafe-caliente">
                        <div class="filter-icon">
                            <i class="fas fa-mug-hot"></i>
                        </div>
                        <span>Café</span>
                    </button>
                    <button class="filter-btn" data-filter="te">
                        <div class="filter-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <span>Té e<br>Infusiones</span>
                    </button>
                    <button class="filter-btn" data-filter="pasteleria">
                        <div class="filter-icon">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        <span>Opciones<br>Dulces</span>
                    </button>
                    <button class="filter-btn" data-filter="snack-salado">
                        <div class="filter-icon">
                            <i class="fas fa-bowl-food"></i>
                        </div>
                        <span>Opciones<br>Saladas</span>
                    </button>
                    <button class="filter-btn" data-filter="bebida-fria">
                        <div class="filter-icon">
                            <i class="fas fa-glass-water"></i>
                        </div>
                        <span>Jugos</span>
                    </button>
                    <button class="filter-btn" data-filter="bebida-fria">
                        <div class="filter-icon">
                            <i class="fas fa-bottle-water"></i>
                        </div>
                        <span>Bebidas</span>
                    </button>
                    <button class="filter-btn" data-filter="vegan">
                        <div class="filter-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <span>Opciones<br>Veganas</span>
                    </button>
                </div>
            </div>

            <?php
            if (isset($products) && !empty($products)) {
                // Agrupar productos por categoría
                $categoriesByKey = [];
                $categoryLabels = [
                    'cafe-caliente' => 'Cafés y Bebidas Calientes',
                    'te' => 'Té e Infusiones Naturales',
                    'bebida-fria' => 'Bebidas Frías',
                    'pasteleria' => 'Pastelería y Dulces',
                    'snack-salado' => 'Snacks y Opciones Saladas'
                ];
                
                foreach ($products as $product) {
                    $cat = $product->category;
                    if (!isset($categoriesByKey[$cat])) {
                        $categoriesByKey[$cat] = [];
                    }
                    $categoriesByKey[$cat][] = $product;
                }
                
                // Mostrar cada categoría
                foreach ($categoryLabels as $key => $label) {
                    if (isset($categoriesByKey[$key])) {
                        echo '<section id="section-' . htmlspecialchars($key) . '">';
                        echo '<h2>' . htmlspecialchars($label) . '</h2>';
                        echo '<div class="products-grid">';
                        
                        foreach ($categoriesByKey[$key] as $product) {
                            // extras
                            $hasExtras = false;
                            if (isset($product->is_coffee)) {
                                $hasExtras = (bool)$product->is_coffee;
                            } else {
                                $coffeeKeywords = ['latte','espresso','cappuccino','moccacino','macchiato','americano','frapp','iced','café','cafe','coffee'];
                                if (isset($product->category) && $product->category === 'cafe-caliente') {
                                    $hasExtras = true;
                                } elseif (isset($product->category) && $product->category === 'bebida-fria') {
                                    foreach ($coffeeKeywords as $kw) {
                                        if (stripos($product->name, $kw) !== false) { $hasExtras = true; break; }
                                    }
                                }
                            }
                            $ingredientsValue = '';
                            if (isset($product->ingredients)) {
                                if (is_array($product->ingredients)) {
                                    $ingredientsValue = htmlspecialchars(implode(', ', $product->ingredients));
                                } else {
                                    $ingredientsValue = htmlspecialchars($product->ingredients);
                                }
                            }

                            $newBadge = '';
                            if (is_array($product) && !empty($product['is_new'])) {
                                $newBadge = '<span class="badge bg-info text-dark ms-2">Nuevo</span>';
                            } elseif (is_object($product) && !empty($product->is_new)) {
                                $newBadge = '<span class="badge bg-info text-dark ms-2">Nuevo</span>';
                            }

                            echo '
                            <div class="product-card-modern" onclick="openProductModal(this)" style="cursor: pointer;" data-has-extras="'.($hasExtras ? '1' : '0').'">
                                <div class="product-image-wrapper">
                                    <img src="' . htmlspecialchars($product->image) . '" alt="' . htmlspecialchars($product->name) . '">
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title">' . htmlspecialchars($product->name) . ' ' . $newBadge . '</h3>
                                    <p class="product-description">' . htmlspecialchars(substr($product->description, 0, 100)) . '</p>
                                    <div class="product-pricing">
                                        <div class="price-section">
                                            <span class="price-new">$' . number_format($product->price, 0, ',', '.') . '</span>
                                        </div>
                                        <button type="button" class="add-button" onclick="openProductModal(this.closest(\'.product-card-modern\')); event.stopPropagation();" title="Agregar al carrito">+</button>
                                    </div>
                                </div>
                                <input type="hidden" class="product-id" value="' . $product->_id . '">
                                <input type="hidden" class="product-name" value="' . htmlspecialchars($product->name) . '">
                                <input type="hidden" class="product-full-description" value="' . htmlspecialchars($product->description) . '">
                                <input type="hidden" class="product-price" value="' . $product->price . '">
                                <input type="hidden" class="product-category" value="' . htmlspecialchars($product->category) . '">
                                <input type="hidden" class="product-ingredients" value="' . $ingredientsValue . '">
                                <input type="hidden" class="product-has-extras" value="' . ($hasExtras ? '1' : '0') . '">
                            </div>';
                        }
                        
                        echo '</div>';
                        echo '</section>';
                    }
                }
            } else {
                echo '<div class="text-center py-5"><p>No hay productos disponibles.</p></div>';
            }
            ?>
        </div>
    </main>
    
    <!-- Modal del Producto -->
    <div class="product-modal" id="productModal">
        <div class="modal-content-product">
            <button class="modal-close-btn" onclick="closeProductModal()">✕</button>

            <h2 class="modal-product-title" id="modalTitle"></h2>
            <p class="modal-product-description" id="modalDescription"></p>

            <div id="modalIngredients" class="modal-ingredients" style="display:none;">
                <h4>Ingredientes</h4>
                <p id="modalIngredientsList"></p>
            </div>

            <div class="extras-section" id="modalExtrasSection">
                <div class="extras-title">
                    ¿Deseas agregar extra?
                    <span>Opcional</span>
                </div>
                <div id="extrasContainer">
                </div>
            </div>

            <div class="quantity-selector">
                <button type="button" class="quantity-btn" onclick="decreaseQuantity()">−</button>
                <span class="quantity-display" id="modalQuantity">1</span>
                <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
            </div>

            <div class="modal-footer">
                <div class="modal-price" id="modalPrice" style="color: white;">$0</div>
                <form method="POST" action="/cart/add" style="display: flex; gap: 10px;">
                    <input type="hidden" name="product_id" id="modalProductId">
                    <input type="hidden" name="quantity" id="modalQuantityInput" value="1">
                    <input type="hidden" name="product_price" id="modalProductPrice">
                    <input type="hidden" name="extras" id="modalExtras" value="{}">
                    <input type="hidden" name="return_url" value="/menu">
                    <button type="submit" class="modal-add-btn">Agregar</button>
                </form>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/partials/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>