<?php
require_once __DIR__ . '/../core/Database.php';

class ContactController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function index() {
        include __DIR__ . '/../views/contact.php';
    }

    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['contact_error'] = 'Método no permitido';
            header('Location: /contact');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');

        // Validaciones
        if (empty($nombre) || empty($email) || empty($mensaje)) {
            $_SESSION['contact_error'] = 'Todos los campos son obligatorios';
            header('Location: /contact');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['contact_error'] = 'Email inválido';
            header('Location: /contact');
            exit;
        }

        // Guardar en la base de datos
        $data = [
            'nombre' => $nombre,
            'email' => $email,
            'mensaje' => $mensaje,
            'fecha' => new MongoDB\BSON\UTCDateTime(),
            'leido' => false
        ];

        $result = $this->db->insert('contactos', $data);

        if ($result) {
            $_SESSION['contact_success'] = '¡Mensaje enviado con éxito! Nos pondremos en contacto contigo lo antes posible.';
        } else {
            $_SESSION['contact_error'] = 'Error al enviar el mensaje. Por favor, intenta de nuevo.';
        }
        
        header('Location: /contact');
        exit;
    }
}
?>
