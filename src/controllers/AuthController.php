<?php

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        // Si ya está logueado, redirigir a home
        if (isset($_SESSION['user_id'])) {
            header('Location: /home');
            exit;
        }
        
        require_once __DIR__ . '/../views/login.php';
    }
    
    public function processLogin() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Email y contraseña son requeridos']);
            return;
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
            return;
        }
        
        if (!$this->userModel->verifyPassword($password, $user->password)) {
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
            return;
        }
        
        // Crear sesión
        $_SESSION['user_id'] = (string)$user->_id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;
        
        // Cookie para persistencia (7 días)
        $cookie_value = base64_encode(json_encode([
            'user_id' => (string)$user->_id,
            'user_role' => $user->role
        ]));
        
        setcookie('coffee_session', $cookie_value, time() + (86400 * 7), '/');
        
        // Determinar redirección según rol
        $redirect = '/home';
        if ($user->role === 'administrador') {
            $redirect = '/admin/dashboard';
        } elseif ($user->role === 'trabajador' || $user->role === 'empleado') {
            $redirect = '/employee/orders';
        } elseif ($user->role === 'repartidor') {
            $redirect = '/delivery/orders';
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'redirect' => $redirect
        ]);
    }
    
    public function logout() {
        session_destroy();
        setcookie('coffee_session', '', time() - 3600, '/');
        header('Location: /home');
        exit;
    }
    
    public function register() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /home');
            exit;
        }
        
        require_once __DIR__ . '/../views/register.php';
    }
    
    public function processRegister() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validaciones
        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email inválido']);
            return;
        }
        
        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
            return;
        }
        
        if ($password !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
            return;
        }
        
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser) {
            echo json_encode(['success' => false, 'message' => 'Este email ya está registrado']);
            return;
        }
        
        // Crear usuario
        try {
            $userId = $this->userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'cliente' // Por defecto los nuevos usuarios son clientes
            ]);
            
            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'Error al crear el usuario']);
                return;
            }
            
            // Generar token de verificación y enviar email
            $token = $this->userModel->generateVerificationToken($email);
            $emailSent = EmailService::sendVerificationEmail($email, $name, $token);

            // Iniciar sesión automáticamente
            $_SESSION['user_id'] = (string)$userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'cliente';
            $_SESSION['email_verified'] = false; // Marcar como no verificado
            
            $cookie_value = base64_encode(json_encode([
                'user_id' => (string)$userId,
                'user_role' => 'cliente'
            ]));
            
            setcookie('coffee_session', $cookie_value, time() + (86400 * 7), '/');
            
            $message = '¡Registro exitoso! Bienvenido';
            if ($emailSent) {
                $message .= '. Te hemos enviado un correo de verificación.';
            }
            
            echo json_encode([
                'success' => true,
                'message' => $message,
                'redirect' => '/home',
                'email_sent' => $emailSent
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al registrar usuario: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Verificar email con token
     */
    public function verifyEmail() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['error'] = 'Token de verificación no válido';
            header('Location: /login');
            exit;
        }
        
        $user = $this->userModel->verifyEmailWithToken($token);
        
        if ($user) {
            // Actualizar sesión si el usuario está logueado
            if (isset($_SESSION['user_email']) && $_SESSION['user_email'] === $user->email) {
                $_SESSION['email_verified'] = true;
            }
            
            $_SESSION['success'] = '¡Email verificado exitosamente!';
            // Generar token de vista con timestamp y firma HMAC
            $stamp = (string)time();
            $secret = getenv('EMAIL_TOKEN_SECRET') ?: 'dev-secret';
            $sig = hash_hmac('sha256', $stamp, $secret);
            header('Location: /auth/verified?t=' . urlencode($stamp) . '&s=' . urlencode($sig));
        } else {
            $_SESSION['error'] = 'El token de verificación es inválido o ha expirado. Solicita un nuevo correo de verificación.';
            header('Location: /login');
        }
        exit;
    }

    /**
     * Vista de verificación con token de tiempo leve y redirección a login
     */
    public function verified() {
        $t = $_GET['t'] ?? '';
        $s = $_GET['s'] ?? '';
        if (!$t || !$s) {
            header('Location: /login');
            exit;
        }
        $secret = getenv('EMAIL_TOKEN_SECRET') ?: 'dev-secret';
        $expected = hash_hmac('sha256', (string)$t, $secret);
        $age = time() - (int)$t;
        if (!hash_equals($expected, $s) || $age < 0 || $age > 300) { // válido por 5 minutos
            header('Location: /login');
            exit;
        }
        require_once __DIR__ . '/../views/verified.php';
    }
    
    /**
     * Reenviar correo de verificación
     */
    public function resendVerification() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_email'])) {
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
            return;
        }
        
        $email = $_SESSION['user_email'];
        $name = $_SESSION['user_name'] ?? 'Usuario';
        
        // Verificar si ya está verificado
        if ($this->userModel->isEmailVerified($email)) {
            echo json_encode(['success' => false, 'message' => 'Tu email ya está verificado']);
            return;
        }
        
        // Generar nuevo token y enviar
        $token = $this->userModel->generateVerificationToken($email);
        $emailSent = EmailService::sendVerificationEmail($email, $name, $token);
        
        if ($emailSent) {
            echo json_encode(['success' => true, 'message' => 'Correo de verificación enviado. Revisa tu bandeja de entrada.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al enviar el correo. Intenta más tarde.']);
        }
    }
}
