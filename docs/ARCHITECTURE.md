# 🏗️ Arquitectura del Proyecto Coffee Shop

## 📐 Patrón MVC Simplificado

Este proyecto implementa un patrón MVC (Model-View-Controller) optimizado para e-commerce con arquitectura limpia y sin duplicaciones.

### 🎯 Flujo de la Aplicación

```
Usuario → Apache → index.php → Router → Controller → Model → MongoDB
                       ↓           ↓          ↓         ↓
                   Sesión    Validación  Lógica   Persistencia
                       ↓           ↓
                   Cookies    Middleware
                       ↓
                    View (PHP/HTML/JS) → Usuario
```

### 🔄 Flujos Específicos

#### Flujo de Compra
```
Cliente → Menu → Carrito → Checkout → Validación Descuento → Pedido → MongoDB
                              ↓                                   ↓
                         Dirección                          Confirmación
                              ↓                                   ↓
                      Método de Pago                     Tracking Tiempo Real
```

#### Flujo Administrativo
```
Admin → Dashboard → Reportes → Exportar PDF/Excel
          ↓            ↓            ↓
       Mensajes    Productos    Gestión Pedidos
          ↓            ↓            ↓
       MongoDB     Soft Delete   Actualizar Estado
```

## 📁 Estructura Detallada

### `/public/index.php` - Punto de Entrada
- Inicia la sesión PHP
- Define constantes de configuración
- Autoload de clases
- **Router simple** que dirige las peticiones
- Middleware de autenticación

### `/core/Database.php` - Capa de Base de Datos
- **Singleton** para conexión a MongoDB
- Métodos: `find()`, `findOne()`, `insert()`, `update()`
- Manejo de errores y logging

### `/models/` - Modelos de Datos

#### `User.php`
```php
- findByEmail($email)      → Buscar usuario por email
- verifyPassword($pass, $hash) → Validar contraseña
- create($data)            → Crear nuevo usuario
```

#### `Product.php`
```php
- getAll()                 → Productos activos (cliente)
- getAllForAdmin()         → Todos los productos (admin)
- getProductById($id)      → Buscar producto por ID
- create($data)            → Crear nuevo producto
- update($id, $data)       → Actualizar producto
- toggleStatus($id)        → Activar/Desactivar (soft delete)
- getTopSelling($limit)    → Productos más vendidos
```

#### `Order.php`
```php
- create($data)            → Crear nuevo pedido
- getByEmail($email)       → Pedidos de un cliente
- getAll()                 → Todos los pedidos
- getByOrderNumber($num)   → Buscar por número de orden
- updateStatus($id, $status) → Actualizar estado
- generateOrderNumber()    → Generar ID único
- getMetrics()             → Estadísticas financieras
- getMonthlyRevenue()      → Ingresos por mes
```

#### `Cart.php`
```php
- Gestión de carrito en sesión
- Cálculos de totales
- Validación de descuentos
```

### `/controllers/` - Controladores

#### `AuthController.php`
- `login()` → Muestra formulario de login
- `processLogin()` → Procesa autenticación
- `register()` → Muestra formulario de registro
- `processRegister()` → Crea nuevo usuario
- `logout()` → Cierra sesión y limpia cookies

#### `HomeController.php`
- `index()` → Página principal (home)
- `menu()` → Menú con productos activos
- `about()` → Página sobre nosotros
- `orders()` → Historial de pedidos del cliente

#### `CartController.php`
- `view()` → Ver carrito
- `add()` → Agregar producto al carrito (sesión)
- `remove()` → Eliminar item del carrito
- `updateQuantity()` → Actualizar cantidad
- `clear()` → Vaciar carrito
- `checkout()` → Vista de checkout con validación de descuento
- `processOrder()` → Procesar pedido y guardar en MongoDB
- `orderConfirmation()` → Página de confirmación
- `checkEmail()` → AJAX para validar elegibilidad de descuento

**Optimización:** Usa `$this->orderModel` como propiedad de clase en vez de múltiples `require_once`

#### `ProductsController.php`
- `index()` → Lista de productos (admin)
- `create()` → Formulario crear producto
- `store()` → Guardar nuevo producto
- `edit()` → Formulario editar producto
- `update()` → Actualizar producto existente
- `toggleStatus()` → Activar/Desactivar producto (soft delete)
- `delete()` → Eliminación física (no usado, se usa toggleStatus)

#### `AdminController.php`
- `dashboard()` → Dashboard con métricas y mensajes recientes
- `reports()` → Reportes financieros por período
- `export()` → Exportar reportes a PDF o Excel (DomPDF)
- `messages()` → Lista completa de mensajes de contacto
- `markMessageRead()` → Marcar mensaje como leído
- `getRecentContactMessages()` → Últimos 5 mensajes para dashboard

#### `EmployeeController.php`
- `orders()` → Lista de pedidos para preparar
- `updateStatus()` → Actualizar estado de pedido (pending → preparing → ready → delivered)

#### `ContactController.php`
- `index()` → Muestra formulario de contacto
- `send()` → Procesa y guarda mensaje en MongoDB
  
**Optimización:** Usa `$_SESSION['contact_success']` y `$_SESSION['contact_error']` para no interferir con otros mensajes del sistema

#### `TrackingController.php`
- `trackOrder()` → Búsqueda y seguimiento en tiempo real
- Polling automático cada 5 segundos para actualizar estado

### `/views/` - Vistas HTML

#### `login.php`
- Formulario de inicio de sesión
- JavaScript con AJAX para login sin recargar
- Bootstrap 5 con estilos inline

#### `home.php`
- Navbar con usuario logueado
- Grid de productos con Bootstrap
- Sistema de tarjetas responsivo

## 🔐 Sistema de Sesiones

### Flujo de Autenticación

1. **Usuario envía credenciales** → AJAX POST a `/auth/login`
2. **AuthController procesa:**
   - Valida email y contraseña
   - Busca usuario en MongoDB
   - Verifica contraseña hasheada
3. **Si es válido:**
   - Crea sesión PHP: `$_SESSION['user_id']`, `user_name`, `user_role`
   - Crea cookie persistente: `coffee_session` (7 días)
   - Retorna JSON con éxito
4. **Frontend redirige** → `/home`

### Middleware de Protección

En `index.php`, antes del router:

```php
if ($uri !== 'login' && $uri !== 'auth/login' && empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
```

## 🗄️ Base de Datos MongoDB

### Colección: `users`
```json
{
  "_id": ObjectId,
  "name": String,
  "email": String (único),
  "password": String (hash bcrypt),
  "role": String (cliente|trabajador|administrador),
  "created_at": Date
}
```

**Índices:**
- `email` (único) - Para búsqueda rápida y prevenir duplicados

### Colección: `products`
```json
{
  "_id": ObjectId,
  "name": String,
  "description": String,
  "price": Number,
  "size": String,
  "icon": String (clase Bootstrap Icons),
  "image": String (URL externa - Postimg),
  "active": Boolean (soft delete),
  "is_new": Boolean,
  "created_at": Date,
  "updated_at": Date
}
```

**Índices:**
- `active` - Para filtrar productos activos en menú público

### Colección: `orders`
```json
{
  "_id": ObjectId,
  "order_number": String (único, ej: "ORD-6923938C4EF5A"),
  "customer_name": String,
  "customer_email": String,
  "customer_phone": String,
  "delivery_type": String (delivery|pickup),
  "delivery_address": String (opcional),
  "delivery_fee": Number,
  "items": Array [
    {
      "product_id": String,
      "name": String,
      "price": Number,
      "quantity": Number,
      "extras": String (JSON),
      "subtotal": Number
    }
  ],
  "subtotal": Number,
  "discount_code": String (opcional, ej: "WELCOME15"),
  "discount_amount": Number,
  "total": Number,
  "payment_method": String (card|cash|transfer),
  "status": String (pending|preparing|ready|delivered),
  "order_date": String (ISO 8601),
  "created_at": Date
}
```

**Índices:**
- `order_number` (único) - Para tracking rápido
- `customer_email` - Para historial de cliente
- `status` - Para filtros en panel empleado/admin
- `order_date` - Para reportes por período

### Colección: `contactos`
```json
{
  "_id": ObjectId,
  "nombre": String,
  "email": String,
  "mensaje": String (text largo),
  "fecha": MongoDB\BSON\UTCDateTime,
  "leido": Boolean (default: false)
}
```

**Índices:**
- `fecha` (descendente) - Para mostrar mensajes recientes
- `leido` - Para filtrar no leídos

## 🔄 Hot-Reload

### Cómo Funciona

1. Docker monta `./src` como volumen en `/var/www/html`
2. Apache sirve archivos directamente desde el volumen
3. **Cualquier cambio** en `src/` se refleja inmediatamente
4. Solo necesitas recargar el navegador

### Lo que se puede editar en caliente:
- ✅ Código PHP (controllers, models, views)
- ✅ HTML/CSS inline en las vistas
- ✅ JavaScript en las vistas
- ✅ Configuración de Apache (requiere restart)

## 🛣️ Rutas Disponibles

### Públicas (Sin autenticación)
| Ruta | Método | Descripción | Controller |
|------|--------|-------------|------------|
| `/` o `/home` | GET | Página principal | HomeController |
| `/menu` | GET | Menú de productos | HomeController |
| `/about` | GET | Sobre nosotros | HomeController |
| `/contact` | GET | Formulario de contacto | ContactController |
| `/contact/send` | POST | Enviar mensaje | ContactController |
| `/login` | GET | Formulario de login | AuthController |
| `/auth/login` | POST | Procesar login | AuthController |
| `/register` | GET | Formulario de registro | AuthController |
| `/auth/register` | POST | Procesar registro | AuthController |
| `/track-order` | GET/POST | Seguimiento de pedidos | TrackingController |

### Carrito (Público, pero sesión necesaria para comprar)
| Ruta | Método | Descripción | Controller |
|------|--------|-------------|------------|
| `/cart` | GET | Ver carrito | CartController |
| `/cart/add` | POST | Agregar al carrito | CartController |
| `/cart/remove` | POST | Eliminar del carrito | CartController |
| `/cart/update-quantity` | POST | Actualizar cantidad | CartController |
| `/cart/clear` | POST | Vaciar carrito | CartController |
| `/checkout` | GET | Página de checkout | CartController |
| `/cart/process-order` | POST | Procesar pedido | CartController |
| `/cart/order-confirmation` | GET | Confirmación de pedido | CartController |

### Cliente Autenticado
| Ruta | Método | Descripción | Controller |
|------|--------|-------------|------------|
| `/order-history` | GET | Historial de pedidos | HomeController |
| `/logout` | GET | Cerrar sesión | AuthController |

### Empleado/Trabajador
| Ruta | Método | Descripción | Controller |
|------|--------|-------------|------------|
| `/employee/orders` | GET | Pedidos a preparar | EmployeeController |
| `/employee/update-status` | POST | Actualizar estado pedido | EmployeeController |

### Administrador
| Ruta | Método | Descripción | Controller |
|------|--------|-------------|------------|
| `/admin/dashboard` | GET | Dashboard con métricas | AdminController |
| `/admin/reports` | GET | Reportes financieros | AdminController |
| `/admin/export` | POST | Exportar PDF/Excel | AdminController |
| `/admin/messages` | GET | Lista de mensajes | AdminController |
| `/admin/mark-message-read` | GET | Marcar como leído | AdminController |
| `/products` | GET | Gestión de productos | ProductsController |
| `/products/create` | GET | Crear producto | ProductsController |
| `/products/store` | POST | Guardar producto | ProductsController |
| `/products/edit` | GET | Editar producto | ProductsController |
| `/products/update` | POST | Actualizar producto | ProductsController |
| `/products/toggle-status` | POST | Activar/Desactivar | ProductsController |

## 🎨 Frontend - Bootstrap 5

### Componentes Usados
- **Grid System** → Layout responsivo
- **Cards** → Tarjetas de productos
- **Navbar** → Navegación principal
- **Forms** → Formularios con validación
- **Alerts** → Mensajes de error/éxito
- **Bootstrap Icons** → Iconografía

### Sin CSS adicional
Todo el estilo está en `<style>` dentro de cada vista usando:
- Variables CSS (`:root`)
- Flexbox y Grid
- Transiciones y hover effects
- Media queries de Bootstrap

## 🔧 Extensibilidad

### Agregar una nueva ruta:

1. **Crear el controlador** en `/controllers/`
2. **Agregar la ruta** en `index.php`:
```php
case 'mi-ruta':
    $controller = new MiController();
    $controller->index();
    break;
```
3. **Crear la vista** en `/views/`

### Agregar un nuevo modelo:

1. **Crear archivo** en `/models/`
2. **Extender funcionalidad**:
```php
class MiModelo {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function miMetodo() {
        return $this->db->find('mi_coleccion');
    }
}
```

## 🐳 Docker

### Servicios

1. **web** (PHP 8.2 + Apache)
   - Extensión MongoDB
   - mod_rewrite habilitado
   - Puerto 8080 → 80

2. **mongodb** (Mongo 7.0)
   - Puerto 27017
   - Volumen persistente

### Volúmenes
- `./src` → `/var/www/html` (hot-reload)
- `mongodb_data` → `/data/db` (persistencia)

## 📊 Monitoreo

```bash
# Logs de Apache/PHP
docker-compose logs -f web

# Logs de MongoDB
docker-compose logs -f mongodb

# Estado de contenedores
docker-compose ps

# Uso de recursos
docker stats coffee_shop_web coffee_shop_db
```

## 🔒 Seguridad Implementada

### Autenticación
- ✅ Contraseñas hasheadas con bcrypt (cost factor 10)
- ✅ Sesiones PHP seguras con regeneración de ID
- ✅ Cookies con tiempo de expiración (7 días)
- ✅ Validación de roles por ruta

### Autorización
- ✅ Middleware de protección de rutas en `index.php`
- ✅ Lista de rutas públicas vs protegidas
- ✅ Verificación de rol en controladores sensibles (admin, employee)
- ✅ Redirección automática a login si sesión expirada

### Validación de Datos
- ✅ Validación de email con `filter_var(FILTER_VALIDATE_EMAIL)`
- ✅ Sanitización de entradas con `trim()` y `htmlspecialchars()`
- ✅ Validación de campos requeridos antes de insertar
- ✅ Validación de descuentos (primer pedido, código válido)

### Base de Datos
- ✅ MongoDB Driver oficial (sin inyección SQL)
- ✅ Queries preparadas automáticamente
- ✅ Escape de HTML en todas las salidas (`htmlspecialchars`)
- ✅ Validación de ObjectId antes de consultas

### Sesiones
- ✅ Variables de sesión específicas por módulo (`$_SESSION['contact_success']` vs `$_SESSION['success']`)
- ✅ Limpieza de sesión en logout
- ✅ Verificación de existencia antes de usar variables de sesión

## 🎨 Frontend

### Tecnologías
- **Bootstrap 5.3.0** - Framework CSS
- **Bootstrap Icons** - Iconografía
- **Vanilla JavaScript** - Sin frameworks pesados
- **AJAX/Fetch API** - Comunicación asíncrona

### Características
- ✅ Diseño responsivo (mobile-first)
- ✅ Paleta de colores temática de cafetería
- ✅ Gradientes y efectos visuales
- ✅ Alerts auto-desaparecen (5 segundos)
- ✅ Polling en tiempo real (tracking)
- ✅ Sin recarga de página en operaciones críticas

### Variables CSS Globales
```css
:root {
  --coffee-brown: #6F4E37;
  --coffee-light: #A67B5B;
  --coffee-cream: #ECE0D1;
  --coffee-dark: #3E2723;
  --coffee-accent: #D4A574;
}
```

## 🔄 Optimizaciones Realizadas

### Eliminación de Código Duplicado
1. **CartController** - Consolidado `require_once` de Order en constructor
2. **HomeController** - Eliminado método `contact()` duplicado
3. **Includes estandarizados** - Todos usan `__DIR__` para consistencia
4. **Sesiones específicas** - Variables de sesión por contexto

### Soft Delete en Productos
- Campo `active: boolean` en lugar de eliminar físicamente
- `getAll()` filtra solo activos para clientes
- `getAllForAdmin()` muestra todos para gestión
- Toggle con un botón en panel admin

### Exportación de Reportes
- **DomPDF 2.0.8** para PDF con estilos HTML/CSS
- **PhpSpreadsheet** para Excel con formato (colores, bordes, anchos)
- Filtros por período (semana, mes, trimestre, año)

### Polling Inteligente
- Actualización cada 5 segundos sin recargar página
- Pausa cuando pestaña está oculta
- Solo actualiza UI si el estado cambió
- Sin console.log en producción

## 📊 Métricas y Analytics

### Dashboard Administrativo
```
┌─────────────────────────────────────────┐
│  💰 Ingresos  │  ✅ Completados        │
│  $XXX,XXX     │  XX pedidos            │
├─────────────────┬───────────────────────┤
│  ⏳ Pendientes │  📊 Promedio          │
│  XX pedidos    │  $X,XXX               │
└─────────────────┴───────────────────────┘

Top 5 Productos Más Vendidos
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
1. Producto A - $XXX
2. Producto B - $XXX
...

Mensajes Recientes
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📧 Nombre - mensaje preview...
```

### Reportes Financieros
- Filtro por período dinámico
- Gráfico de ingresos mensuales
- Desglose por estado de pedido
- Exportación en 2 formatos

## 🔧 Extensibilidad

### Agregar un nuevo módulo completo:

1. **Crear modelo** en `/models/MiModelo.php`
```php
class MiModelo {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance();
    }
}
```

2. **Crear controlador** en `/controllers/MiController.php`
```php
class MiController {
    private $model;
    public function __construct() {
        $this->model = new MiModelo();
    }
}
```

3. **Agregar rutas** en `/public/index.php`
```php
case 'mi-ruta':
    $controller = new MiController();
    $controller->metodo();
    break;
```

4. **Crear vista** en `/views/mi-vista.php`
```php
<?php include __DIR__ . '/partials/header.php'; ?>
<!-- Contenido -->
<?php include __DIR__ . '/partials/footer.php'; ?>
```

## 🐳 Docker

### Servicios

1. **web** (PHP 8.2 + Apache)
   - Extensión MongoDB habilitada
   - mod_rewrite habilitado
   - Hot-reload con volumen montado
   - Puerto 8081:80

2. **mongodb** (Mongo 7.0)
   - Puerto 27017
   - Volumen persistente
   - Inicialización automática con `init-db.js`

### Volúmenes
- `./src` → `/var/www/html` (código con hot-reload)
- `mongodb_data` → `/data/db` (persistencia de base de datos)

## 🚀 Próximas Mejoras Sugeridas

### Backend
- [ ] Paginación en listas largas (productos, pedidos)
- [ ] Sistema de notificaciones push
- [ ] API RESTful para integración externa
- [ ] Cache con Redis para consultas frecuentes
- [ ] Logging estructurado con niveles

### Frontend
- [ ] PWA con Service Workers
- [ ] Optimización de imágenes (WebP, lazy loading)
- [ ] Dark mode
- [ ] Internacionalización (i18n)

### DevOps
- [ ] CI/CD con GitHub Actions
- [ ] Tests automatizados (PHPUnit, Playwright)
- [ ] Backup automático de MongoDB
- [ ] Monitoring con Prometheus/Grafana
- [ ] Staging environment

---

**Arquitectura moderna, limpia y escalable diseñada para e-commerce** 🚀☕
