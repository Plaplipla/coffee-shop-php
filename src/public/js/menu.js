// Funcionalidad del menú y modal de productos
let currentQuantity = 1;
let extrasQuantities = {
    descafeinado: 0,
    extraShot: 0,
    syrupVainilla: 0,
    syrupChocolate: 0
};

const extras = [
    { id: 'descafeinado', name: 'Descafeinado', price: 1000 },
    { id: 'extraShot', name: 'Extra shot de café', price: 990 },
    { id: 'syrupVainilla', name: 'Syrup Vainilla', price: 990 },
    { id: 'syrupChocolate', name: 'Syrup Chocolate', price: 990 }
];

let basePrice = 0;

function openProductModal(element) {
    const productId = element.querySelector('.product-id').value;
    const productName = element.querySelector('.product-name').value;
    // Prefer full description when available
    const fullDescEl = element.querySelector('.product-full-description');
    const shortDescEl = element.querySelector('.product-description');
    const productDescription = fullDescEl ? fullDescEl.value : (shortDescEl ? shortDescEl.value : '');
    const productPrice = element.querySelector('.product-price').value;
    const productCategoryEl = element.querySelector('.product-category');
    const productIngredientsEl = element.querySelector('.product-ingredients');
    const productHasExtrasEl = element.querySelector('.product-has-extras');
    const hasExtrasFlag = (productHasExtrasEl && productHasExtrasEl.value === '1') || (element.dataset && element.dataset.hasExtras === '1');
    
    basePrice = parseFloat(productPrice);
    
    document.getElementById('modalTitle').textContent = productName;
    document.getElementById('modalDescription').textContent = productDescription;
    // Ingredients
    var ingredientsContainer = document.getElementById('modalIngredients');
    var ingredientsList = document.getElementById('modalIngredientsList');
    if (productIngredientsEl && productIngredientsEl.value && productIngredientsEl.value.trim() !== '') {
        ingredientsList.textContent = productIngredientsEl.value;
        if (ingredientsContainer) ingredientsContainer.style.display = 'block';
    } else {
        if (ingredientsContainer) ingredientsContainer.style.display = 'none';
        if (ingredientsList) ingredientsList.textContent = '';
    }
    document.getElementById('modalProductId').value = productId;
    document.getElementById('modalQuantity').textContent = '1';
    document.getElementById('modalQuantityInput').value = '1';
    currentQuantity = 1;
    
    // Resetear extras
    extrasQuantities = {
        descafeinado: 0,
        extraShot: 0,
        syrupVainilla: 0,
        syrupChocolate: 0
    };
    
    // Mostrar/ocultar sección de extras según el tipo de producto
    var extrasSection = document.getElementById('modalExtrasSection');
    if (extrasSection) {
        if (hasExtrasFlag) {
            extrasSection.style.display = '';
            updateExtrasUI();
        } else {
            extrasSection.style.display = 'none';
            extrasQuantities = { descafeinado: 0, extraShot: 0, syrupVainilla: 0, syrupChocolate: 0 };
            // Clear container
            var extrasContainer = document.getElementById('extrasContainer');
            if (extrasContainer) extrasContainer.innerHTML = '';
        }
    } else {
        updateExtrasUI();
    }
    updateModalPrice();
    
    // Mostrar offcanvas si existe, sino fallback al modal personalizado
    const offEl = document.getElementById('productOffcanvas');
    if (offEl && window.bootstrap && bootstrap.Offcanvas) {
        let inst = bootstrap.Offcanvas.getInstance(offEl);
        if (!inst) inst = new bootstrap.Offcanvas(offEl);
        inst.show();
    } else {
        const modalEl = document.getElementById('productModal');
        if (modalEl) {
            modalEl.classList.add('active');
            document.body.classList.add('modal-open');
        }
    }
}

function closeProductModal() {
    const offEl = document.getElementById('productOffcanvas');
    if (offEl && window.bootstrap && bootstrap.Offcanvas) {
        const inst = bootstrap.Offcanvas.getInstance(offEl);
        if (inst) inst.hide();
    }
    const modalEl = document.getElementById('productModal');
    if (modalEl) {
        modalEl.classList.remove('active');
        document.body.classList.remove('modal-open');
    }
    currentQuantity = 1;
}

function increaseQuantity() {
    currentQuantity++;
    updateModalQuantity();
}

function decreaseQuantity() {
    if (currentQuantity > 1) {
        currentQuantity--;
        updateModalQuantity();
    }
}

function updateModalQuantity() {
    document.getElementById('modalQuantity').textContent = currentQuantity;
    document.getElementById('modalQuantityInput').value = currentQuantity;
    updateModalPrice();
}

function updateModalPrice() {
    let totalExtrasPrice = 0;
    
    Object.keys(extrasQuantities).forEach(key => {
        const extra = extras.find(e => e.id === key);
        if (extra) {
            totalExtrasPrice += extra.price * extrasQuantities[key];
        }
    });
    
    const pricePerUnit = basePrice + totalExtrasPrice;
    const totalPrice = pricePerUnit * currentQuantity;
    
    document.getElementById('modalPrice').textContent = '$' + new Intl.NumberFormat('es-CL').format(Math.round(totalPrice));
    document.getElementById('modalProductPrice').value = Math.round(pricePerUnit);
    document.getElementById('modalExtras').value = JSON.stringify(extrasQuantities);
}

function updateExtrasUI() {
    const extrasContainer = document.getElementById('extrasContainer');
    extrasContainer.innerHTML = '';
    
    extras.forEach((extra, index) => {
        const extrasKey = Object.keys(extrasQuantities)[index];
        const quantity = extrasQuantities[extrasKey];
        
        const extrasHTML = `
            <div class="extra-item">
                <div>
                    <div class="extra-name">${extra.name}</div>
                    <div class="extra-price">+ $${extra.price.toLocaleString('es-CL')}</div>
                </div>
                <div class="quantity-selector" style="margin: 0; border: none; padding: 0;">
                    <button type="button" class="quantity-btn" onclick="decreaseExtra('${extrasKey}')">−</button>
                    <span class="quantity-display" id="extra-${extrasKey}">${quantity}</span>
                    <button type="button" class="quantity-btn" onclick="increaseExtra('${extrasKey}')">+</button>
                </div>
            </div>
        `;
        extrasContainer.innerHTML += extrasHTML;
    });
}

function increaseExtra(extraKey) {
    extrasQuantities[extraKey]++;
    updateExtrasUI();
    updateModalPrice();
}

function decreaseExtra(extraKey) {
    if (extrasQuantities[extraKey] > 0) {
        extrasQuantities[extraKey]--;
        updateExtrasUI();
        updateModalPrice();
    }
}

// Cerrar modal al hacer click fuera
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('productModal');
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeProductModal();
        }
    });
    
    // Tecla ESC para cerrar
    // Filtros de categoría - Índice con scroll
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            if (filter === 'all') {
                // Scroll al inicio del menú
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (filter === 'vegan') {
                // Para opciones veganas, scroll a cualquier sección (ejemplo)
                const section = document.getElementById('section-pasteleria');
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            } else {
                // Scroll a la sección correspondiente
                const sectionId = 'section-' + filter;
                const section = document.getElementById(sectionId);
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProductModal();
        }
    });
});