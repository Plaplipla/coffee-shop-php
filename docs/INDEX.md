# 📚 Índice de Documentación - Coffee Shop

Bienvenido al proyecto Coffee Shop E-commerce. Esta es tu guía para navegar toda la documentación.

> **📍 Ubicación:** Toda la documentación está en la carpeta `docs/`

---

## 🚀 Para Empezar

### 1. **[QUICKSTART.md](QUICKSTART.md)** 🏃‍♂️ INICIO RÁPIDO
- 🚀 3 pasos para levantar el proyecto
- 👥 Usuarios de prueba
- 🔥 Cómo funciona el hot-reload
- 🛑 Cómo detener el proyecto

### 2. **[../README.md](../README.md)** 📖 DOCUMENTACIÓN PRINCIPAL
- 🌟 Características del proyecto
- 📁 Estructura del proyecto
- 🛠️ Instalación completa
- 📱 Páginas implementadas
- 🔒 Sistema de sesiones

---

## 🔍 Para Entender el Proyecto

### 3. **[PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md)** 📊 VISIÓN GENERAL
- 📂 Estructura visual
- 🎨 Capturas conceptuales
- 📊 Datos de ejemplo
- 🛠️ Stack tecnológico
- 📈 Métricas del proyecto
- 🎓 Casos de uso

### 4. **[ARCHITECTURE.md](ARCHITECTURE.md)** 🏗️ ARQUITECTURA TÉCNICA
- 📐 Patrón MVC explicado
- 🎯 Flujo de la aplicación
- 🔄 Cómo funciona el hot-reload
- 🛣️ Rutas disponibles
- 🔒 Sistema de seguridad
- 🚀 Optimizaciones implementadas

### 5. **[BUSINESS_FLOW.md](BUSINESS_FLOW.md)** 📊 FLUJO DE NEGOCIO
- 👥 Roles de usuario (cliente, empleado, admin)
- 🛍️ Flujo completo del cliente
- 👨‍🍳 Flujo del empleado
- 👨‍💼 Flujo del administrador
- 🔄 Ciclo de vida del pedido
- 💰 Sistema de descuentos
- 📦 Gestión de productos

### 6. **[DATABASE.md](DATABASE.md)** 🗄️ BASE DE DATOS
- 📊 Modelo de datos con Mermaid
- 📦 4 colecciones detalladas
- 🔍 Índices y optimización
- 🔗 Relaciones entre colecciones
- 📝 Consultas comunes
- 🔐 Seguridad de datos

---

## 🛠️ Para Desarrollar

### 7. **[COMMANDS.md](COMMANDS.md)** ⌨️ COMANDOS
- 🚀 Comandos básicos Docker
- 📊 Monitoreo y logs
- 🗄️ Comandos MongoDB
- 🐘 Comandos PHP
- 🌐 Comandos Apache
- 🔧 Desarrollo y debug
- 📦 Backup y restore
- ⌨️ Aliases útiles

### 8. **Scripts de Inicio**
- **[start.sh](start.sh)** - Script para Linux/Mac
- **[start.bat](start.bat)** - Script para Windows
- **[init-db.js](init-db.js)** - Inicializar base de datos

---

## ✅ Para Verificar

### 9. **[CHECKLIST.md](CHECKLIST.md)** ✔️ LISTA DE VERIFICACIÓN
- 🔍 Checklist de instalación
- 🧪 Tests de autenticación
- 🎨 Verificación visual
- 🔧 Checklist técnico
- 🐛 Errores comunes
- ✅ Verificación final

### 10. **[IMPLEMENTATION_CHECKLIST.md](IMPLEMENTATION_CHECKLIST.md)** 📋 CHECKLIST IMPLEMENTACIÓN
- ✅ Estado de implementación de features
- 🔄 Progreso del proyecto
- 📝 Tareas completadas y pendientes

### 11. **[STRIPE_PAYMENT.md](STRIPE_PAYMENT.md)** 💳 PAGOS CON STRIPE
- 💳 Integración de Stripe completa
- 🔧 Configuración paso a paso
- 🧪 Guía de pruebas
- 🎨 Flujo visual de pago
- ✅ Estado: Probado y funcionando

---

## 📁 Archivos de Configuración

### Docker
- **[docker-compose.yml](docker-compose.yml)** - Orquestación de servicios
- **[Dockerfile](Dockerfile)** - Imagen PHP personalizada
- **[apache-config.conf](apache-config.conf)** - Configuración Apache

### Otros
- **[.gitignore](.gitignore)** - Archivos ignorados por Git
- **[.env.example](.env.example)** - Variables de entorno ejemplo

---

## 💻 Código Fuente

### 📂 Estructura del Código

```
src/
├── controllers/          # 🎮 Controladores
│   ├── AuthController.php
│   └── HomeController.php
│
├── models/              # 📊 Modelos
│   ├── User.php
│   └── Product.php
│
├── views/               # 🎨 Vistas
│   ├── login.php
│   └── home.php
│
├── core/                # ⚙️ Núcleo
│   └── Database.php
│
└── public/              # 🌐 Entrada
    ├── index.php
    └── .htaccess
```

---

## 🎯 Guías Según tu Objetivo

### 🆕 Soy nuevo, ¿por dónde empiezo?
1. Lee **[PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md)** para entender qué es el proyecto
2. Sigue **[QUICKSTART.md](QUICKSTART.md)** para levantarlo
3. Usa **[CHECKLIST.md](CHECKLIST.md)** para verificar que funciona

### 🔨 Quiero desarrollar features nuevas
1. Lee **[ARCHITECTURE.md](ARCHITECTURE.md)** para entender la estructura técnica
2. Lee **[BUSINESS_FLOW.md](BUSINESS_FLOW.md)** para entender los flujos
3. Consulta **[DATABASE.md](DATABASE.md)** para el modelo de datos
4. Usa **[COMMANDS.md](COMMANDS.md)** para comandos útiles
5. Edita archivos en `src/` (hot-reload activo)

### 📊 Quiero entender el negocio
1. Lee **[BUSINESS_FLOW.md](BUSINESS_FLOW.md)** para roles, flujos y reglas
2. Consulta **[DATABASE.md](DATABASE.md)** para ver qué se almacena
3. Revisa **[PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md)** para la visión completa

### 🗄️ Necesito trabajar con la base de datos
1. Lee **[DATABASE.md](DATABASE.md)** para el modelo completo
2. Usa **[COMMANDS.md](COMMANDS.md)** sección MongoDB
3. Consulta **[ARCHITECTURE.md](ARCHITECTURE.md)** para patrones de acceso

### 🚀 Quiero ponerlo en producción
1. Lee la sección de seguridad en **[ARCHITECTURE.md](ARCHITECTURE.md)**
2. Revisa las mejores prácticas en **[README.md](../README.md)**
3. Usa **[COMMANDS.md](COMMANDS.md)** para backup y monitoreo

---

## 📊 Mapeo Rápido

| Necesito... | Voy a... |
|-------------|----------|
| Empezar desde cero | [QUICKSTART.md](QUICKSTART.md) |
| Entender el proyecto | [PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md) |
| Ver la arquitectura | [ARCHITECTURE.md](ARCHITECTURE.md) |
| Entender flujos de negocio | [BUSINESS_FLOW.md](BUSINESS_FLOW.md) |
| Ver modelo de base de datos | [DATABASE.md](DATABASE.md) |
| Configurar pagos con Stripe | [STRIPE_PAYMENT.md](STRIPE_PAYMENT.md) |
| Comandos útiles | [COMMANDS.md](COMMANDS.md) |
| Verificar instalación | [CHECKLIST.md](CHECKLIST.md) |
| Documentación completa | [../README.md](../README.md) |

---

## 🔗 Enlaces Rápidos

### Accesos Web
- 🌐 Aplicación: http://localhost:8081
- 🔐 Login: http://localhost:8081/login
- 🏠 Home: http://localhost:8081/home

### Comandos Más Usados
```bash
# Iniciar
docker-compose up -d --build

# Ver logs
docker-compose logs -f

# Detener
docker-compose down

# Reiniciar BD
docker exec -i coffee_shop_db mongosh < init-db.js
```

### Usuarios Demo
- 📧 admin@coffee.com | 🔑 123456
- 📧 trabajador@coffee.com | 🔑 123456
- 📧 cliente@coffee.com | 🔑 123456

---

## 📈 Nivel de Prioridad de Lectura

### 🔴 Prioridad Alta (Leer primero)
1. [QUICKSTART.md](QUICKSTART.md)
2. [PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md)
3. [CHECKLIST.md](CHECKLIST.md)

### 🟡 Prioridad Media (Desarrolladores)
4. [ARCHITECTURE.md](ARCHITECTURE.md)
5. [BUSINESS_FLOW.md](BUSINESS_FLOW.md)
6. [DATABASE.md](DATABASE.md)
7. [COMMANDS.md](COMMANDS.md)
8. [STRIPE_PAYMENT.md](STRIPE_PAYMENT.md)

### 🟢 Prioridad Baja (Referencia)
8. [../README.md](../README.md)
9. [IMPLEMENTATION_CHECKLIST.md](IMPLEMENTATION_CHECKLIST.md)

---

## 🎨 Documentos por Tipo

### 📖 Documentación General
- ../README.md
- PROJECT_OVERVIEW.md

### 🏗️ Arquitectura y Diseño
- ARCHITECTURE.md
- BUSINESS_FLOW.md
- DATABASE.md

### 🚀 Guías de Inicio
- QUICKSTART.md

### 🛠️ Referencia Técnica
- COMMANDS.md
- STRIPE_PAYMENT.md

### ✅ Verificación
- CHECKLIST.md
- IMPLEMENTATION_CHECKLIST.md

### 📜 Scripts
- start.sh
- start.bat
- init-db.js

---

### 🔍 Búsqueda Rápida

### ¿Cómo hacer...?

**¿Cómo levantar el proyecto?**
→ [QUICKSTART.md](QUICKSTART.md) sección "Iniciar el Proyecto"

**¿Cómo configurar pagos con Stripe?**
→ [STRIPE_PAYMENT.md](STRIPE_PAYMENT.md) sección "Configuración Rápida"

**¿Cómo funciona el login?**
→ [ARCHITECTURE.md](ARCHITECTURE.md) sección "Sistema de Sesiones"

**¿Cómo agregar un producto?**
→ [COMMANDS.md](COMMANDS.md) sección "Manipulación de Datos"

**¿Cómo ver los logs?**
→ [COMMANDS.md](COMMANDS.md) sección "Monitoreo"

**¿Cómo reiniciar la BD?**
→ [COMMANDS.md](COMMANDS.md) sección "Reiniciar Base de Datos"

**¿Por qué no funciona el login?**
→ [CHECKLIST.md](CHECKLIST.md) sección "Checklist de Errores Comunes"

**¿Cómo verificar que todo funciona?**
→ [CHECKLIST.md](CHECKLIST.md) todas las secciones

**¿Qué es el hot-reload?**
→ [ARCHITECTURE.md](ARCHITECTURE.md) sección "Hot-Reload"

**¿Cómo funciona el flujo de compra?**
→ [BUSINESS_FLOW.md](BUSINESS_FLOW.md) sección "Flujo del Cliente"

**¿Qué se almacena en la base de datos?**
→ [DATABASE.md](DATABASE.md) sección "Colecciones"

**¿Cómo funcionan los roles?**
→ [BUSINESS_FLOW.md](BUSINESS_FLOW.md) sección "Roles de Usuario"

---

## 💡 Tips de Navegación

1. **Ctrl + F** para buscar dentro de un documento
2. Los enlaces internos te llevan directamente a la sección
3. Cada documento tiene un propósito específico
4. Empieza por PROJECT_OVERVIEW.md para el panorama general
5. COMMANDS.md es tu referencia rápida constante
6. BUSINESS_FLOW.md explica todo el negocio con diagramas
7. DATABASE.md incluye modelo Mermaid completo

---

## 📞 ¿Necesitas Ayuda?

1. **Busca** en este índice tu necesidad
2. **Ve** al documento correspondiente
3. **Usa** Ctrl + F para buscar palabras clave
4. **Consulta** [COMMANDS.md](COMMANDS.md) para comandos específicos
5. **Revisa** [CHECKLIST.md](CHECKLIST.md) para verificar el setup

---

## 🎯 Resumen Ultra-Rápido

```bash
# 1. Levantar proyecto
docker-compose up -d --build

# 2. Esperar 10 segundos
sleep 10

# 3. Inicializar BD
docker exec -i coffee_shop_db mongosh < init-db.js

# 4. Abrir navegador
# → http://localhost:8081

# 5. Login
# → admin@coffee.com / 123456

# ¡Listo! ☕
```

---

**📚 Este índice se actualiza automáticamente con el proyecto**

*Última actualización: 23 de noviembre de 2025*
