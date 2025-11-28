<?php
/**
 * EmailService - Servicio para envío de correos electrónicos
 * Usa PHPMailer con SMTP (Gmail)
 */

// Cargar PHPMailer (asumiendo que está en vendor/phpmailer)
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    
    /**
     * Enviar correo simple
     */
    public static function sendSimpleEmail(string $toEmail, string $subject, string $htmlBody, string $textBody = ''): bool {
        // Cargar variables de entorno si existen
        self::loadEnv();
        
        $mail = new PHPMailer(true);
        
        try {
            // Configuración SMTP
            $mail->isSMTP();
            $mail->Host       = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('SMTP_USER');
            $mail->Password   = getenv('SMTP_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = intval(getenv('SMTP_PORT') ?: 587);
            
            // Configuración del correo
            $fromEmail = getenv('SMTP_FROM_EMAIL') ?: getenv('SMTP_USER');
            $fromName  = getenv('SMTP_FROM_NAME') ?: 'Coffee Shop';
            
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail);
            
            // Contenido
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = $textBody ?: strip_tags($htmlBody);
            
            return $mail->send();
        } catch (Exception $e) {
            error_log('Error al enviar email: ' . $e->getMessage());
            error_log('PHPMailer Error: ' . $mail->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Enviar correo de verificación
     */
    public static function sendVerificationEmail(string $toEmail, string $name, string $token): bool {
        $appUrl = getenv('APP_URL') ?: 'http://localhost:8081';
        $verifyUrl = $appUrl . '/auth/verify-email?token=' . urlencode($token);
        
        $html = self::getVerificationEmailTemplate($name, $verifyUrl);
        $text = "Hola {$name},\n\nVerifica tu correo haciendo clic en el siguiente enlace:\n{$verifyUrl}\n\nGracias,\nCoffee Shop";
        
        return self::sendSimpleEmail($toEmail, 'Verifica tu correo - Coffee Shop', $html, $text);
    }
    
    /**
     * Enviar correo de confirmación de orden
     */
    public static function sendOrderConfirmation(string $toEmail, string $name, string $orderNumber, float $total): bool {
        $appUrl = getenv('APP_URL') ?: 'http://localhost:8081';
        $trackUrl = $appUrl . '/track-order?order=' . urlencode($orderNumber);
        
        $html = self::getOrderConfirmationTemplate($name, $orderNumber, $total, $trackUrl);
        $text = "Hola {$name},\n\nTu pedido #{$orderNumber} ha sido confirmado.\nTotal: \$" . number_format($total, 0, ',', '.') . "\n\nRastrear pedido: {$trackUrl}\n\nGracias,\nCoffee Shop";
        
        return self::sendSimpleEmail($toEmail, 'Confirmación de pedido #' . $orderNumber, $html, $text);
    }
    
    /**
     * Template HTML para verificación de email
     */
    private static function getVerificationEmailTemplate(string $name, string $verifyUrl): string {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6f4e37; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
                .button { display: inline-block; background: #6f4e37; color: white !important; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>☕ Coffee Shop</h1>
                </div>
                <div class='content'>
                    <h2>¡Hola {$name}!</h2>
                    <p>Gracias por registrarte en Coffee Shop. Para completar tu registro, necesitamos verificar tu correo electrónico.</p>
                    <p>Por favor haz clic en el siguiente botón para verificar tu cuenta:</p>
                    <p style='text-align: center;'>
                        <a href='{$verifyUrl}' class='button'>Verificar mi correo</a>
                    </p>
                    <p>Si no puedes hacer clic en el botón, copia y pega el siguiente enlace en tu navegador:</p>
                    <p style='word-break: break-all; color: #666; font-size: 14px;'>{$verifyUrl}</p>
                    <p style='margin-top: 30px; color: #666;'><small>Si no creaste una cuenta en Coffee Shop, puedes ignorar este correo.</small></p>
                </div>
                <div class='footer'>
                    <p>&copy; 2025 Coffee Shop. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Template HTML para confirmación de orden
     */
    private static function getOrderConfirmationTemplate(string $name, string $orderNumber, float $total, string $trackUrl): string {
        $formattedTotal = '$' . number_format($total, 0, ',', '.');
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6f4e37; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
                .order-box { background: white; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #6f4e37; }
                .button { display: inline-block; background: #6f4e37; color: white !important; padding: 12px 30px; text-decoration: none; border-radius: 6px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>☕ Coffee Shop</h1>
                </div>
                <div class='content'>
                    <h2>¡Hola {$name}!</h2>
                    <p>Tu pedido ha sido confirmado exitosamente. ¡Gracias por tu compra!</p>
                    <div class='order-box'>
                        <p><strong>Número de pedido:</strong> {$orderNumber}</p>
                        <p><strong>Total:</strong> {$formattedTotal}</p>
                    </div>
                    <p>Puedes rastrear el estado de tu pedido en tiempo real haciendo clic en el siguiente botón:</p>
                    <p style='text-align: center;'>
                        <a href='{$trackUrl}' class='button'>Rastrear mi pedido</a>
                    </p>
                    <p style='margin-top: 30px; color: #666;'><small>Te notificaremos cuando tu pedido esté listo.</small></p>
                </div>
                <div class='footer'>
                    <p>&copy; 2025 Coffee Shop. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Cargar variables de entorno desde .env
     */
    private static function loadEnv() {
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                if (!getenv($name)) {
                    putenv("$name=$value");
                }
            }
        }
    }
}
