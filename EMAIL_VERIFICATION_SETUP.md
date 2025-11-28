# 📧 Sistema de Verificación de Email - Instrucciones de Instalación

## ✅ Cambios Realizados

Se ha implementado un sistema completo de verificación de correo electrónico usando PHPMailer y Gmail SMTP.

### Archivos Creados/Modificados:

1. **`src/core/EmailService.php`** - Servicio para envío de correos
2. **`src/models/User.php`** - Métodos de verificación agregados
3. **`src/controllers/AuthController.php`** - Métodos de verificación y reenvío
4. **`src/public/index.php`** - Rutas de verificación agregadas
5. **`src/views/partials/header.php`** - Banner de verificación
6. **`.env.example`** - Variables SMTP agregadas

---

## 📦 Instalación Requerida

### 1. Instalar Composer (si no lo tienes)

**En Windows:**
```powershell
# Descargar e instalar desde:
https://getcomposer.org/Composer-Setup.exe

# O usar Chocolatey:
choco install composer
```

**Verificar instalación:**
```powershell
composer --version
```

### 2. Instalar PHPMailer

```powershell
cd "c:\Users\valer\Downloads\coffee-shop-main\coffee-shop-main\src"
composer require phpmailer/phpmailer
```

Esto creará la carpeta `vendor/` con PHPMailer instalado.

---

## 🔧 Configuración

### 1. Crear archivo `.env`

Copia `.env.example` a `.env`:
```powershell
cd "c:\Users\valer\Downloads\coffee-shop-main\coffee-shop-main"
copy .env.example .env
```

### 2. Configurar Gmail SMTP

**Paso 1: Crear App Password en Google**
1. Ve a: https://myaccount.google.com/security
2. Activa **Verificación en 2 pasos**
3. Busca **Contraseñas de aplicaciones**
4. Selecciona **Correo** → **Windows**
5. Copia la clave de 16 caracteres generada

**Paso 2: Editar `.env`**
```env
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=tu_correo@gmail.com
SMTP_PASS=xxxx xxxx xxxx xxxx    # App Password de 16 chars
SMTP_FROM_EMAIL=tu_correo@gmail.com
SMTP_FROM_NAME=Coffee Shop
```

### 3. Asegurar que `.env` está en `.gitignore`

El archivo `.env` NO debe subirse a GitHub (ya está en `.gitignore`).

---

## 🧪 Probar el Sistema

### 1. Reiniciar el contenedor Docker

```powershell
cd "c:\Users\valer\Downloads\coffee-shop-main\coffee-shop-main"
./stop.sh
./start.sh
```

### 2. Registrar un nuevo usuario

1. Ve a: http://localhost:8081/register
2. Completa el formulario con un email real (tuyo)
3. Haz clic en **Registrarse**
4. Verás el mensaje: "¡Registro exitoso! Te hemos enviado un correo de verificación."

### 3. Verificar el correo

1. Revisa tu bandeja de entrada (puede tardar 1-2 minutos)
2. Abre el email de "Coffee Shop - Verifica tu correo"
3. Haz clic en el botón **Verificar mi correo**
4. Serás redirigido a la aplicación con el mensaje: "¡Email verificado exitosamente!"

### 4. Reenviar verificación (si es necesario)

Si no recibes el correo:
1. En la aplicación, verás un banner amarillo: "Verifica tu correo electrónico"
2. Haz clic en **Reenviar correo**
3. Se enviará un nuevo correo de verificación

---

## 🔍 Troubleshooting

### Error: "composer: command not found"

**Solución:** Instala Composer desde https://getcomposer.org/

### Error: "SMTP ERROR: Failed to connect"

**Posibles causas:**
1. App Password incorrecto → Genera uno nuevo en Google
2. SMTP_USER no es tu Gmail → Verifica el correo en `.env`
3. Verificación en 2 pasos no activada → Actívala en Google

**Verificar configuración:**
```php
// Agregar esto temporalmente en EmailService.php línea 30
$mail->SMTPDebug = 2; // Ver debug de SMTP
```

### No recibo el correo

1. **Revisa spam/correo no deseado**
2. **Verifica logs del contenedor:**
   ```powershell
   docker logs coffee_shop_web
   ```
3. **Prueba enviar desde terminal PHP:**
   ```powershell
   docker exec -it coffee_shop_web php -r "require 'vendor/autoload.php'; echo 'PHPMailer OK';"
   ```

### Error: "Class 'EmailService' not found"

**Solución:** Verifica que el autoload está bien:
```php
// En index.php debe existir:
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../controllers/' . $class . '.php',
        __DIR__ . '/../models/' . $class . '.php',
        __DIR__ . '/../core/' . $class . '.php'  // ← Aquí está EmailService
    ];
    // ...
});
```

---

## 📊 Estructura de Base de Datos

Los usuarios ahora tienen estos campos adicionales:

```javascript
{
  _id: ObjectId,
  name: "Juan Pérez",
  email: "juan@example.com",
  password: "$2y$10$...", // hasheada
  role: "cliente",
  created_at: ISODate("2025-11-28T..."),
  email_verified: false,  // ← NUEVO
  email_verification_token: "abc123...",  // ← NUEVO
  email_verification_token_expires: ISODate("2025-11-29T...")  // ← NUEVO (24 hrs)
}
```

---

## 🎯 Funcionalidades Implementadas

### ✅ Para el Usuario
- Al registrarse, recibe un correo de verificación automáticamente
- Banner amarillo visible hasta que verifique su email
- Puede reenviar el correo de verificación si no lo recibe
- Link de verificación expira en 24 horas

### ✅ Para el Sistema
- Envío de correos vía Gmail SMTP (PHPMailer)
- Templates HTML profesionales para los correos
- Tokens seguros generados con `random_bytes()`
- Validación de expiración de tokens
- Logging de errores de envío

### 📧 Correos que se envían:
1. **Verificación de email** - Al registrarse
2. **Confirmación de orden** - Al hacer un pedido (opcional, ya implementado)

---

## 🔐 Seguridad

- ✅ App Password de Gmail (no contraseña real)
- ✅ Tokens aleatorios de 64 caracteres
- ✅ Tokens expiran en 24 horas
- ✅ Variables sensibles en `.env` (no en Git)
- ✅ Validación de tokens en servidor

---

## 🚀 Próximos Pasos (Opcional)

### Mejoras sugeridas:
- [ ] Correo de bienvenida después de verificar
- [ ] Correo de restablecimiento de contraseña
- [ ] Notificaciones de orden por email
- [ ] Unsubscribe link en correos
- [ ] Migrar a SendGrid/Mailgun para producción

---

## 📝 Notas Finales

- **Desarrollo:** Usa Gmail SMTP (gratis, 500 correos/día)
- **Producción:** Considera SendGrid/Mailgun/AWS SES (mejor entregabilidad)
- **No subas `.env` a GitHub** - Ya está en `.gitignore`
- **Logs:** Revisa `docker logs coffee_shop_web` si hay errores

---

**¿Necesitas ayuda?** Revisa los logs o contacta al equipo de desarrollo.
