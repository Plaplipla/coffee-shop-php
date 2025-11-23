<?php
session_start();

// Configuración de la base de datos
define('MONGO_HOST', getenv('MONGO_HOST') ?: 'localhost');
define('MONGO_PORT', getenv('MONGO_PORT') ?: '27017');
define('MONGO_DB', getenv('MONGO_DB') ?: 'coffee_shop');

// URL base
define('BASE_URL', '/');

// Autoload de clases
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../controllers/' . $class . '.php',
        __DIR__ . '/../models/' . $class . '.php',
        __DIR__ . '/../core/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Enrutador simple
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

// RUTAS PÚBLICAS
$publicRoutes = [
    '', 'home', 'login', 'auth/login', 'register', 'auth/register',
    'cart', 'cart/add', 'cart/remove', 'cart/update-quantity', 'cart/clear',
    'checkout', 'cart/process-order', 'cart/order-confirmation', 'menu', 'contact', 'about',
    'employee/orders', 'admin/dashboard', 'admin/reports', 'admin/export', 'track-order', 'order-history'
];

// Verificar sesión solo para rutas protegidas
if (!in_array($uri, $publicRoutes) && empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

//  Rutas
switch ($uri) {
    case '':
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;
    
    case 'menu':
        $controller = new HomeController();
        $controller->menu();
        break;
        
    case 'contact':
        $controller = new HomeController();
        $controller->contact();
        break;
        
    case 'about':
        $controller = new HomeController();
        $controller->about();
        break;
    
    case 'order-history':
        $controller = new HomeController();
        $controller->orders();
        break;
    
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;
    
    case 'auth/login':
        $controller = new AuthController();
        $controller->processLogin();
        break;
    
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    
    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;
    
    case 'auth/register':
        $controller = new AuthController();
        $controller->processRegister();
        break;
        
    // RUTAS DEL CARRITO
    case 'cart':
        $controller = new CartController();
        $controller->view();
        break;
        
    case 'cart/add':
        $controller = new CartController();
        $controller->add();
        break;
        
    case 'cart/remove':
        $controller = new CartController();
        $controller->remove();
        break;
        
    case 'cart/update-quantity':
        $controller = new CartController();
        $controller->updateQuantity();
        break;
        
    case 'cart/clear':
        $controller = new CartController();
        $controller->clear();
        break;

    // RUTAS DE CHECKOUT
    case 'checkout':
        $controller = new CartController();
        $controller->checkout();
        break;
        
    case 'cart/process-order':
        $controller = new CartController();
        $controller->processOrder();
        break;

    case 'cart/order-confirmation':
        $controller = new CartController();
        $controller->orderConfirmation();
        break;

    case 'employee/orders':
        $controller = new EmployeeController();
        $controller->orders();
        break;

    case 'employee/update-status':
        $controller = new EmployeeController();
        $controller->updateStatus();
        break;

    case 'products':
        $controller = new ProductsController();
        $controller->index();
        break;

    case 'products/create':
        $controller = new ProductsController();
        $controller->create();
        break;

    case 'products/store':
        $controller = new ProductsController();
        $controller->store();
        break;

    case 'products/edit':
        $controller = new ProductsController();
        $controller->edit();
        break;

    case 'products/update':
        $controller = new ProductsController();
        $controller->update();
        break;

    case 'products/delete':
        $controller = new ProductsController();
        $controller->delete();
        break;

    case 'admin/dashboard':
        $controller = new AdminController();
        $controller->dashboard();
        break;

    case 'admin/reports':
        $controller = new AdminController();
        $controller->reports();
        break;

    case 'admin/export':
        $controller = new AdminController();
        $controller->export();
        break;

    case 'track-order':
        $controller = new TrackingController();
        $controller->trackOrder();
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}
?>