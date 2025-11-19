<?php
require_once __DIR__ . '/../models/Product.php';

class ProductsController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        // Acceso restringido a empleados/administradores
        if (!isset($_SESSION['user_id']) || 
            (!in_array($_SESSION['user_role'] ?? '', ['empleado','trabajador','administrador']))) {
            $_SESSION['error'] = 'Acceso denegado. Se requiere rol de empleado.';
            header('Location: /home');
            exit;
        }

        $products = $this->productModel->getAll();
        require __DIR__ . '/../views/products.php';
    }

    public function create() {
        // show creation form
        if (!isset($_SESSION['user_id']) || 
            (!in_array($_SESSION['user_role'] ?? '', ['empleado','trabajador','administrador']))) {
            $_SESSION['error'] = 'Acceso denegado.';
            header('Location: /home'); exit;
        }
        require __DIR__ . '/../views/products_create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /products'); exit; }
        if (!isset($_SESSION['user_id']) || 
            (!in_array($_SESSION['user_role'] ?? '', ['empleado','trabajador','administrador']))) {
            $_SESSION['error'] = 'Acceso denegado.';
            header('Location: /home'); exit;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'category' => $_POST['category'] ?? '',
            'price' => floatval(str_replace([',',' '],'', $_POST['price'] ?? 0)),
            'stock' => intval($_POST['stock'] ?? 0),
            'image' => $_POST['image'] ?? '',
            'description' => $_POST['description'] ?? '',
            'is_new' => isset($_POST['is_new']) ? true : false
        ];

        $res = $this->productModel->create($data);
        if ($res) $_SESSION['success'] = 'Producto creado'; else $_SESSION['error'] = 'Error al crear producto';
        header('Location: /products'); exit;
    }

    public function edit() {
        if (!isset($_SESSION['user_id']) || 
            (!in_array($_SESSION['user_role'] ?? '', ['empleado','trabajador','administrador']))) {
            $_SESSION['error'] = 'Acceso denegado.';
            header('Location: /home'); exit;
        }
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: /products'); exit; }
        $product = $this->productModel->getProductById($id);
        require __DIR__ . '/../views/products_edit.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /products'); exit; }
        if (!isset($_SESSION['user_id']) || 
            (!in_array($_SESSION['user_role'] ?? '', ['empleado','trabajador','administrador']))) {
            $_SESSION['error'] = 'Acceso denegado.';
            header('Location: /home'); exit;
        }
        $id = $_POST['id'] ?? null;
        if (!$id) { $_SESSION['error']='ID inválido'; header('Location:/products'); exit; }
        $data = [
            'name' => $_POST['name'] ?? '',
            'category' => $_POST['category'] ?? '',
            'price' => floatval(str_replace([',',' '],'', $_POST['price'] ?? 0)),
            'stock' => intval($_POST['stock'] ?? 0),
            'image' => $_POST['image'] ?? '',
            'description' => $_POST['description'] ?? '',
            'is_new' => isset($_POST['is_new']) ? true : false
        ];
        $res = $this->productModel->update($id, $data);
        if ($res) $_SESSION['success'] = 'Producto actualizado'; else $_SESSION['error'] = 'Error al actualizar';
        header('Location: /products'); exit;
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || 
            (!in_array($_SESSION['user_role'] ?? '', ['empleado','trabajador','administrador']))) {
            $_SESSION['error'] = 'Acceso denegado.';
            header('Location: /home'); exit;
        }
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: /products'); exit; }
        $res = $this->productModel->delete($id);
        if ($res) $_SESSION['success']='Producto eliminado'; else $_SESSION['error']='Error al eliminar';
        header('Location: /products'); exit;
    }
}
?>