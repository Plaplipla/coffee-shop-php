// Script para inicializar la base de datos con datos de ejemplo
// Este script se ejecutará automáticamente al iniciar MongoDB

db = db.getSiblingDB('coffee_shop');

// Limpiar colecciones existentes
db.users.drop();
db.products.drop();
db.orders.drop();

// Crear usuarios de ejemplo
// Password hash para "123456": $2y$10$k3mAM9vNjsDIKdLq3SYIgeKi3B5fw15Lpx4uBnxrftZ3PexqFL.8K
db.users.insertMany([
    {
        name: "Cliente Demo",
        email: "cliente@coffee.com",
        password: "$2y$10$k3mAM9vNjsDIKdLq3SYIgeKi3B5fw15Lpx4uBnxrftZ3PexqFL.8K", // 123456
        role: "cliente",
        created_at: new Date()
    },
    {
        name: "Trabajador Demo",
        email: "trabajador@coffee.com",
        password: "$2y$10$k3mAM9vNjsDIKdLq3SYIgeKi3B5fw15Lpx4uBnxrftZ3PexqFL.8K", // 123456
        role: "trabajador",
        created_at: new Date()
    },
    {
        name: "Administrador Demo",
        email: "admin@coffee.com",
        password: "$2y$10$k3mAM9vNjsDIKdLq3SYIgeKi3B5fw15Lpx4uBnxrftZ3PexqFL.8K", // 123456
        role: "administrador",
        created_at: new Date()
    }
]);

// Crear productos de café actualizados
db.products.insertMany([
    // Cafés Calientes
    {
        name: "Espresso Clásico",
        description: "Una explosión de sabor y aroma en cada sorbo. Intenso, equilibrado y lleno de carácter, despierta los sentidos y deja una sensación cálida y energizante, ideal para comenzar el día o disfrutar un momento de pausa.",
        price: 2300,
        size: "Regular",
        icon: "bi bi-cup-hot-fill",
        image: "images/products/espresso.png",
        category: "cafe-caliente",
        is_coffee: true,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Cappuccino Cremoso",
        description: "Clásico y espumoso, mezcla espresso, leche caliente y abundante espuma para ofrecer una textura cremosa y un sabor balanceado entre la fuerza del café y la suavidad de la leche.",
        price: 3200,
        size: "Grande",
        icon: "bi bi-cup-straw",
        image: "images/products/cappuccino.png",
        category: "cafe-caliente",
        is_coffee: true,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Latte Suave",
        description: "Una mezcla perfecta de espresso y leche vaporizada, con una fina capa de espuma. Su sabor suave y cremoso lo hace ideal para disfrutar en cualquier momento del día.",
        price: 3500,
        size: "Grande",
        icon: "bi bi-cup-hot",
        image: "images/products/latte.png",
        category: "cafe-caliente",
        is_coffee: true,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Moccacino con Chocolate",
        description: "Una deliciosa combinación de espresso, leche vaporizada y chocolate, coronado con crema batida y virutas de chocolate.",
        price: 3800,
        size: "Grande",
        icon: "bi bi-cup-hot-fill",
        image: "images/products/cappuccino.png",
        category: "cafe-caliente",
        is_coffee: true,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Caramel Macchiato",
        description: "Un espresso con leche vaporizada y un toque de caramelo, coronado con espuma y un delicioso sirope de caramelo.",
        price: 3700,
        size: "Grande",
        icon: "bi bi-cup-straw",
        image: "images/products/latte.png",
        category: "cafe-caliente",
        is_coffee: true,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Americano",
        description: "Un espresso diluido con agua caliente para crear una bebida de sabor fuerte pero menos concentrado que un espresso tradicional.",
        price: 2500,
        size: "Regular",
        icon: "bi bi-cup",
        image: "images/products/espresso.png",
        category: "cafe-caliente",
        is_coffee: true,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    
    // Tés
    {
        name: "Té Verde",
        description: "Té verde de alta calidad con propiedades antioxidantes, sabor suave y refrescante.",
        price: 2200,
        size: "Regular",
        icon: "bi bi-cup-hot",
        image: "images/products/green-tea.png",
        category: "te",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Té Negro",
        description: "Té negro de origen regional, con un sabor intenso y aromático, ideal para comenzar el día con energía.",
        price: 2400,
        size: "Regular",
        icon: "bi bi-cup-hot-fill",
        image: "images/products/black-tea.png",
        category: "te",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Té Blanco",
        description: "Té blanco delicado y aromático, con notas florales y un sabor suave y elegante.",
        price: 2500,
        size: "Regular",
        icon: "bi bi-cup-hot",
        image: "images/products/white-tea.png",
        category: "te",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Super Matcha",
        description: "Polvo de matcha premium preparado con leche caliente para un sabor intenso y energizante.",
        price: 3200,
        size: "Regular",
        icon: "bi bi-cup-hot-fill",
        image: "images/products/matcha.png",
        category: "te",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Hierba Natural",
        description: "Infusión natural de hierbas aromáticas, ideal para relajarse y desintoxicarse.",
        price: 2200,
        size: "Regular",
        icon: "bi bi-cup-hot",
        image: "images/products/green-tea.png",
        category: "te",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Té Oolong",
        description: "Té oolong semi-fermentado con sabor equilibrado entre el té verde y negro, con notas florales.",
        price: 2600,
        size: "Regular",
        icon: "bi bi-cup-hot",
        image: "images/products/black-tea.png",
        category: "te",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    
    // Bebidas Frías
    {
        name: "Iced Latte",
        description: "La combinación perfecta de espresso y leche fría, servida con hielo para una experiencia refrescante.",
        price: 3200,
        size: "Grande",
        icon: "bi bi-cup-straw",
        image: "images/products/iced-latte.png",
        category: "bebida-fria",
        is_coffee: true,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Frappé",
        description: "Una deliciosa mezcla de café, hielo y leche batida, endulzada al gusto y coronada con crema batida.",
        price: 3500,
        size: "Grande",
        icon: "bi bi-cup-straw",
        image: "images/products/frappe.png",
        category: "bebida-fria",
        is_coffee: true,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Jugo Natural",
        description: "Jugo fresco exprimido del día, preparado con frutas naturales sin conservantes ni azúcares añadidos.",
        price: 3800,
        size: "Grande",
        icon: "bi bi-cup-straw",
        image: "images/products/juice.png",
        category: "bebida-fria",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Limonada",
        description: "Refrescante limonada casera hecha con limones frescos, agua y un toque de miel natural.",
        price: 2800,
        size: "Grande",
        icon: "bi bi-cup-straw",
        image: "images/products/juice.png",
        category: "bebida-fria",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Smoothie",
        description: "Bebida batida elaborada con frutas frescas, yogur y hielo, para una opción saludable y deliciosa.",
        price: 4000,
        size: "Grande",
        icon: "bi bi-cup-straw",
        image: "images/products/iced-latte.png",
        category: "bebida-fria",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Té Frío",
        description: "Té enfriado con hielo, disponible en variados sabores, ideal para acompañar tus snacks favoritos.",
        price: 2500,
        size: "Grande",
        icon: "bi bi-cup-straw",
        image: "images/products/white-tea.png",
        category: "bebida-fria",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    
    // Pastelería
    {
        name: "Cheesecake de Frutas Rojas",
        description: "Un delicioso cheesecake cremoso con base de galleta, coronado con una mezcla de frutos rojos frescos.",
        price: 4200,
        size: "Porción",
        icon: "bi bi-cake",
        image: "images/products/cheesecake.png",
        category: "pasteleria",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "NYCookie's",
        description: "Galletas grandes y crujientes con chispas de chocolate, inspiradas en las famosas cookies neoyorquinas.",
        price: 2800,
        size: "Unidad",
        icon: "bi bi-cookie",
        image: "images/products/cookies.png",
        category: "pasteleria",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Bizcocho Red Velvet",
        description: "Esponjoso bizcocho red velvet con frosting de queso crema, un clásico irresistible.",
        price: 4500,
        size: "Porción",
        icon: "bi bi-cake2",
        image: "images/products/bizcocho.png",
        category: "pasteleria",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Kuchen de Manzana",
        description: "Kuchen casero de manzana con canela, una delicia tradicional chilena perfecta para acompañar tu café.",
        price: 3800,
        size: "Porción",
        icon: "bi bi-cake2",
        image: "images/products/kuchen.png",
        category: "pasteleria",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Brownie Casero",
        description: "Chocolate intenso, textura húmeda y ese toque de nueces que lo hace irresistible. Ideal para acompañar tu café.",
        price: 3500,
        size: "Porción",
        icon: "bi bi-cake2",
        image: "images/products/brownie.jpg",
        category: "pasteleria",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Croissant de Mantequilla",
        description: "Clásico, dorado y hojaldrado. Hecho con mantequilla de verdad y horneado cada mañana para mantener la frescura.",
        price: 3200,
        size: "Unidad",
        icon: "bi bi-cookie",
        image: "images/products/croissant.jpg",
        category: "pasteleria",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    
    // Snacks Salados
    {
        name: "Sandwich Gourmet",
        description: "Pan artesanal relleno con jamón de pavo, queso suizo, lechuga, tomate y nuestra mayonesa especial.",
        price: 4800,
        size: "Completo",
        icon: "bi bi-basket",
        image: "images/products/sandwich.png",
        category: "snack-salado",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Quiche Artesanal",
        description: "Deliciosa quiche con masa quebrada, rellena de huevo, crema, jamón y queso gratinado.",
        price: 4500,
        size: "Porción",
        icon: "bi bi-pie-chart",
        image: "images/products/quiche.png",
        category: "snack-salado",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Wrap de Pollo",
        description: "Tortilla de trigo rellena con pechuga de pollo a la parrilla, lechuga, tomate, cebolla y aderezo ranch.",
        price: 4200,
        size: "Completo",
        icon: "bi bi-basket",
        image: "images/products/wrap-pollo.png",
        category: "snack-salado",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Empanada Napolitana",
        description: "Empanada crujiente rellena de jamón, queso derretido y salsa de tomate, una opción clásica y sabrosa.",
        price: 3200,
        size: "Unidad",
        icon: "bi bi-basket",
        image: "images/products/empanada.png",
        category: "snack-salado",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    },
    {
        name: "Focaccia de jamón con hierbas",
        description: "Pan focaccia casero decorado con jamón serrano, hierbas aromáticas y aceite de oliva extra virgen.",
        price: 3800,
        size: "Porción",
        icon: "bi bi-basket",
        image: "images/products/focaccia.png",
        category: "snack-salado",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: true,
        created_at: new Date()
    },
    {
        name: "Ensalada Cesar",
        description: "Fresca ensalada con lechuga romana, crutones caseros, parmesano rallado y nuestro aderezo césar artesanal.",
        price: 5200,
        size: "Completo",
        icon: "bi bi-basket",
        image: "images/products/ensalada.png",
        category: "snack-salado",
        is_coffee: false,
        stock: 15,
        active: true,
        is_new: false,
        created_at: new Date()
    }
]);

print("✅ Base de datos inicializada correctamente");
print("📊 Usuarios creados: " + db.users.count());
print("☕ Productos creados: " + db.products.count());

// Crear órdenes de prueba
db.orders.insertMany([
    {
        order_number: "ORD-691CE4A0A069E96",
        customer_name: "Juan Pérez",
        customer_email: "juan@example.com",
        customer_phone: "912345678",
        delivery_type: "delivery",
        delivery_address: "Calle Principal 123, Apt 4B",
        delivery_fee: 3000,
        items: [
            {
                cart_item_key: "1_abc123",
                product_id: "1",
                name: "Espresso Clásico",
                price: 2300,
                quantity: 2,
                image: "images/products/espresso.png"
            }
        ],
        total: 7600,
        order_date: new Date(),
        status: "pending",
        created_at: new Date()
    }
]);

print("🎯 Órdenes de prueba creadas: " + db.orders.count());
