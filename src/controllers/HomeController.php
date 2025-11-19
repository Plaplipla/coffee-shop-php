<?php
class HomeController {
    
    public function index() {
        include __DIR__ . '/../views/home.php';
    }
    
    public function menu() {
        $productModel = new Product();
        $products = $productModel->getAll();
        
        include __DIR__ . '/../views/menu.php';
    }
    
    public function contact() {
        include __DIR__ . '/../views/contact.php';
    }
    
    public function about() {
        include __DIR__ . '/../views/about.php';
    }
}
?>