# ☕ Coffee Shop - E-commerce de Cafetería

Aplicación web de e-commerce para una cafetería desarrollada con PHP MVC, MongoDB, y Bootstrap.

## 🚀 Características

- ✅ Dockerizado con hot-reload
- ✅ Sistema de autenticación con cookies
- ✅ 3 tipos de usuarios: Cliente, Trabajador, Administrador
- ✅ **💳 Pasarela de pagos con Stripe** (Tarjeta, Efectivo, Online)
- ✅ Panel administrativo con reportes financieros y mensajes de contacto
- ✅ Seguimiento de pedidos en tiempo real con actualización automática
- ✅ Sistema de contacto con almacenamiento en MongoDB
- ✅ Gestión de productos con activar/desactivar (soft delete)
- ✅ Exportación de reportes a PDF y Excel
- ✅ Arquitectura MVC simple y ordenada
- ✅ Bootstrap 5 con colores temáticos de cafetería
- ✅ MongoDB como base de datos
- ✅ Código limpio sin duplicaciones

## 📁 Estructura del Proyecto

```
php-proyect/
├── docker-compose.yml          # Configuración Docker
├── Dockerfile                  # Imagen PHP con extensión MongoDB
├── apache-config.conf          # Configuración Apache
├── init-db.js                  # Script de inicialización de BD
├── start.sh / start.bat        # 🚀 Iniciar aplicación
├── stop.sh / stop.bat          # 🛑 Detener aplicación
├── clean.sh / clean.bat        # 🧹 Limpieza completa Docker
├── docs/                       # 📚 Documentación completa
│   ├── INDEX.md               # Índice de documentación
│   ├── QUICKSTART.md          # Guía de inicio rápido
│   ├── ARCHITECTURE.md        # Arquitectura técnica
│   ├── COMMANDS.md            # Comandos útiles
│   ├── TROUBLESHOOTING.md     # Solución de problemas
│   ├── CHECKLIST.md           # Lista de verificación
│   └── ...más documentos
└── src/
    ├── controllers/            # Controladores MVC
    │   ├── AdminController.php        # Panel administrativo y mensajes
    │   ├── AuthController.php         # Autenticación
    │   ├── CartController.php         # Carrito y checkout
    │   ├── ContactController.php      # Formulario de contacto
    │   ├── EmployeeController.php     # Panel empleados
    │   ├── HomeController.php         # Páginas públicas
    │   ├── ProductsController.php     # Gestión de productos
    │   └── TrackingController.php     # Seguimiento de pedidos
    ├── core/                   # Núcleo del sistema
    │   └── Database.php
    ├── models/                 # Modelos
    │   ├── Cart.php
    │   ├── Order.php
    │   ├── Product.php
    │   └── User.php
    ├── public/                 # Punto de entrada web
    │   ├── css/
    │   │   └── style.css
    │   ├── images/
    │   │   ├── icons-rrss/
    │   │   └── ... más imagenes del home
    │   ├── js/
    │   │   └── menu.js
    │   ├── .htaccess
    │   └── index.php
    ├── views/                  # Vistas
    │   ├── admin/              # Panel administrador
    │   │   ├── dashboard.php   # Dashboard con métricas y mensajes
    │   │   ├── reports.php     # Reportes financieros
    │   │   └── messages.php    # Gestión de mensajes de contacto
    │   ├── employee/
    │   │   └── orders.php
    │   ├── partials/           # Componentes reutilizables
    │   │   ├── header.php      # Router de navbars
    │   │   ├── header-admin.php
    │   │   ├── header-employee.php
    │   │   └── footer.php
    │   ├── about.php
    │   ├── cart.php
    │   ├── checkout.php
    │   ├── contact.php         # Formulario de contacto
    │   ├── home.php
    │   ├── login.php
    │   ├── menu.php
    │   ├── order-confirmation.php
    │   ├── order-history.php
    │   ├── products.php        # Gestión de productos (admin)
    │   ├── products_create.php
    │   ├── products_edit.php
    │   ├── register.php
    │   └── track-order.php     # Seguimiento en tiempo real
    
```

## 🛠️ Instalación y Uso

### Opción 1: Script Automatizado (Recomendado) 🚀

#### Linux/Mac:
```bash
chmod +x start.sh
./start.sh
```

#### Windows:
```bash
start.bat
```

### Opción 2: Manual

#### 1. Levantar el proyecto con Docker

```bash
# Construir e iniciar los contenedores
docker-compose up -d --build

# Ver logs
docker-compose logs -f
```

#### 2. Inicializar la base de datos

```bash
# Esperar 10 segundos para que MongoDB esté listo
# Ejecutar script de inicialización
docker exec -i coffee_shop_db mongosh < init-db.js
```

#### 3. Acceder a la aplicación

Abre tu navegador en: **http://localhost:8081**

## 👥 Usuarios de Prueba

| Rol           | Email                    | Contraseña |
|---------------|--------------------------|------------|
| Cliente       | cliente@coffee.com       | 123456     |
| Trabajador    | trabajador@coffee.com    | 123456     |
| Administrador | admin@coffee.com         | 123456     |

## 🔥 Hot-Reload

El proyecto está configurado para **hot-reload automático**:

- Los cambios en archivos PHP se reflejan inmediatamente
- No necesitas reconstruir el contenedor
- Edita el código en `src/` y recarga el navegador

## 🎨 Colores de la Aplicación

La aplicación usa una paleta de colores inspirada en cafetería:

- **Brown Coffee**: `#6F4E37` - Marrón café principal
- **Light Coffee**: `#A67B5B` - Café claro
- **Cream**: `#ECE0D1` - Crema
- **Dark Coffee**: `#3E2723` - Café oscuro
- **Accent**: `#D4A574` - Acento dorado

## 📱 Páginas Implementadas

### 👨‍💼 Panel Administrador

**Acceso:** `/admin/dashboard` (solo admin)

#### Dashboard (`/admin/dashboard`)
- 📊 4 tarjetas con métricas principales:
  - Ingresos totales
  - Pedidos completados
  - Pedidos pendientes
  - Valor promedio por pedido
- 📈 Resumen general con tasa de conversión
- ⭐ Top 5 productos más vendidos
- 📧 Mensajes de contacto recientes (5 últimos)
- 🔗 Acciones rápidas a reportes y gestión de pedidos

#### Reportes Financieros (`/admin/reports`)
- 📅 Selector de períodos: Semana, Mes, Trimestre, Año
- 💰 Resumen de ingresos del período
- 📦 Desglose de pedidos por estado con barras de progreso
- 📊 Ingresos mensuales desglosados
- 📥 Exportación de reportes en PDF o Excel con estilos profesionales

#### Gestión de Productos (`/products`)
- 📝 Crear, editar productos
- 🔄 Activar/Desactivar productos (soft delete)
- 🖼️ Imágenes desde URLs externas (Postimg)
- 👁️ Vista diferenciada para admin (ve todos) y clientes (solo activos)

#### Mensajes de Contacto (`/admin/messages`)
- 📨 Lista completa de mensajes recibidos
- ✅ Marcar como leído/no leído
- 📧 Email clickable para responder
- 📅 Fecha y hora de envío

#### Seguridad
- 🔐 Acceso restringido solo a rol 'administrador'
- 🚫 Redirección automática para usuarios no autorizados
- ✅ Validación en cada petición

### Login (`/login`)
- Formulario de autenticación
- Validación con AJAX
- Mensajes de error/éxito
- Usuarios de prueba visibles

### Home (`/` o `/home`)
- Navbar con usuario logueado
- Listado de productos de café
- Tarjetas de productos con Bootstrap
- Protección de sesión (redirección si no está logueado)

### Seguimiento de Pedidos (`/track-order`)
- 🔍 Búsqueda de pedidos por número
- ⏱️ Actualización automática cada 5 segundos (sin recargar página)
- 📍 Estado del pedido con línea de tiempo interactiva
- 🔔 Cambios de estado reflejados en tiempo real
- 🎨 Alertas con colores según estado (info, warning, success)

### Contacto (`/contact`)
- 📝 Formulario de contacto con validación
- ✉️ Mensajes almacenados en MongoDB (colección `contactos`)
- ✅ Notificación de éxito con auto-cierre (5 segundos)
- 📱 Diseño responsivo con gradientes y tarjetas
- 🔔 Mensajes específicos para contacto (no interfieren con otros alerts)

## 🔒 Sistema de Sesiones

- Sesiones PHP estándar
- Cookies persistentes (7 días)
- Middleware de verificación automática
- Redirección a login si la sesión expira

## 🛑 Detener el Proyecto

### Opción 1: Script Automatizado (Recomendado) 🛑

#### Linux/Mac:
```bash
chmod +x stop.sh
./stop.sh
```

#### Windows:
```bash
stop.bat
```

**Este script:**
- ✅ Detiene todos los contenedores
- ✅ Mantiene los volúmenes intactos (no se pierden datos)
- ✅ Mantiene las imágenes descargadas
- ✅ Permite reiniciar rápidamente con `./start.sh`

### Opción 2: Manual

```bash
# Detener contenedores (mantiene volúmenes e imágenes)
docker-compose down
```

## 🧹 Limpieza Completa de Docker

Si necesitas liberar espacio o hacer una limpieza completa del sistema Docker:

### Linux/Mac:
```bash
chmod +x clean.sh
./clean.sh
```

### Windows:
```bash
clean.bat
```

**⚠️ ADVERTENCIA: Este script eliminará:**
- ❌ Todos los contenedores detenidos
- ❌ Todas las redes no utilizadas
- ❌ Todos los volúmenes no utilizados (base de datos)
- ❌ Todas las imágenes no utilizadas
- ❌ Todo el caché de compilación

**Después de ejecutar clean, necesitarás:**
- Ejecutar `./start.sh` nuevamente
- Las imágenes se descargarán desde cero
- La base de datos se inicializará desde cero

## 📦 Comandos Útiles

```bash
# Ver logs del contenedor web
docker-compose logs -f web

# Ver logs de MongoDB
docker-compose logs -f mongodb

# Acceder al contenedor PHP
docker exec -it coffee_shop_web bash

# Acceder a MongoDB
docker exec -it coffee_shop_db mongosh coffee_shop

# Reiniciar servicios
docker-compose restart
```

## 🔧 Tecnologías

- **Backend**: PHP 8.2
- **Base de datos**: MongoDB 7.0
- **Frontend**: Bootstrap 5, JavaScript (AJAX)
- **Servidor**: Apache 2.4
- **Contenedores**: Docker & Docker Compose

## 📊 Funcionalidades Implementadas

### ✅ Completadas
- 🛒 Carrito de compras con descuentos
- 📦 Gestión completa de pedidos
- 👨‍💼 Panel de administración con dashboard y reportes
- 📊 Reportes financieros con exportación PDF/Excel
- 🔍 Seguimiento de pedidos en tiempo real (polling cada 5s)
- 👥 Autenticación multirol (Cliente, Empleado, Admin)
- 💳 **Pasarela de pagos con Stripe** (Online, Tarjeta al recibir, Efectivo)
- 💳 Confirmación de órdenes con resumen detallado
- 📧 Sistema de contacto con panel de mensajes para admin
- 🎨 Gestión de productos con soft delete (activar/desactivar)
- 🖼️ Imágenes de productos desde URLs externas
- 🧹 Código optimizado sin duplicaciones

## 👨‍💻 Desarrollo

El proyecto está configurado para desarrollo rápido:

1. Edita archivos en `src/`
2. Recarga el navegador
3. Los cambios se reflejan automáticamente

No es necesario reiniciar Docker para cambios en el código.

## 📚 Documentación Completa

Toda la documentación está organizada en la carpeta `docs/`:

| Documento | Descripción |
|-----------|-------------|
| [INDEX.md](docs/INDEX.md) | 📑 Índice general de toda la documentación |
| [README.md](docs/README.md) | 📖 Resumen de la documentación |
| [QUICKSTART.md](docs/QUICKSTART.md) | 🚀 Guía de inicio rápido (3 pasos) |
| [PROJECT_OVERVIEW.md](docs/PROJECT_OVERVIEW.md) | 🎯 Visión general del proyecto |
| [ARCHITECTURE.md](docs/ARCHITECTURE.md) | 🏗️ Arquitectura técnica detallada |
| [BUSINESS_FLOW.md](docs/BUSINESS_FLOW.md) | 💼 Flujo de negocio y procesos |
| [DATABASE.md](docs/DATABASE.md) | 🗄️ Estructura de base de datos |
| [COMMANDS.md](docs/COMMANDS.md) | ⌨️ Lista completa de comandos útiles |
| [CHECKLIST.md](docs/CHECKLIST.md) | ✅ Lista de verificación del proyecto |
| [IMPLEMENTATION_CHECKLIST.md](docs/IMPLEMENTATION_CHECKLIST.md) | 📋 Checklist de implementación |

### 💳 Documentación de Stripe (Pagos)

| Documento | Descripción |
|-----------|-------------|
| [STRIPE_PAYMENT.md](docs/STRIPE_PAYMENT.md) | 💳 Guía completa de integración de Stripe |

### 🎯 Por Dónde Empezar

- **Nuevo en el proyecto?** → Lee [docs/QUICKSTART.md](docs/QUICKSTART.md)
- **Configurar pagos con Stripe?** → Lee [docs/STRIPE_PAYMENT.md](docs/STRIPE_PAYMENT.md)
- **Quieres entender la arquitectura?** → Lee [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)
- **Ver estructura de BD?** → Consulta [docs/DATABASE.md](docs/DATABASE.md)
- **Necesitas comandos?** → Revisa [docs/COMMANDS.md](docs/COMMANDS.md)

---

**¡Disfruta tu café! ☕**
