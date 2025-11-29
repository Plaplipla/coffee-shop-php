# ☕ Flujo de Negocio - Coffee Shop E-commerce

## 📋 Tabla de Contenidos
- [Visión General](#visión-general)
- [Roles de Usuario](#roles-de-usuario)
- [Flujo del Cliente](#flujo-del-cliente)
- [Flujo del Empleado](#flujo-del-empleado)
- [Flujo del Administrador](#flujo-del-administrador)
- [Ciclo de Vida del Pedido](#ciclo-de-vida-del-pedido)
- [Sistema de Descuentos](#sistema-de-descuentos)
- [Gestión de Productos](#gestión-de-productos)
- [Sistema de Comunicación](#sistema-de-comunicación)

---

## 🎯 Visión General

Coffee Shop es un sistema de e-commerce B2C (Business to Consumer) especializado en productos de cafetería con tres actores principales: **Clientes**, **Empleados** y **Administradores**.

### Propósito del Negocio
- Vender productos de café y bebidas relacionadas
- Ofrecer servicio a domicilio o retiro en tienda
- Gestionar eficientemente el proceso de pedidos
- Analizar métricas de ventas y rendimiento
- Mantener comunicación directa con los clientes

---

## 👥 Roles de Usuario

### 1. 🛍️ Cliente (cliente)
**Acceso:** Público con registro opcional

**Permisos:**
- ✅ Ver catálogo de productos
- ✅ Agregar productos al carrito
- ✅ Realizar pedidos (con o sin cuenta)
- ✅ Aplicar código de descuento (primer pedido)
- ✅ Seguir estado de pedido en tiempo real
- ✅ Ver historial de pedidos (si está registrado)
- ✅ Contactar con la cafetería

**Restricciones:**
- ❌ No puede ver productos desactivados
- ❌ No puede acceder a panel administrativo
- ❌ No puede modificar pedidos una vez enviados

### 2. 👨‍🍳 Empleado/Trabajador (trabajador)
**Acceso:** Requiere cuenta y login

**Permisos:**
- ✅ Ver todos los pedidos pendientes y en preparación
- ✅ Actualizar estado de pedidos
- ✅ Marcar pedidos como listos
- ✅ Marcar pedidos como entregados

**Restricciones:**
- ❌ No puede ver métricas financieras
- ❌ No puede gestionar productos
- ❌ No puede ver reportes completos
- ❌ No puede exportar datos

### 3. 👨‍💼 Administrador (administrador)
**Acceso:** Requiere cuenta con rol admin

**Permisos:**
- ✅ Acceso completo al dashboard
- ✅ Ver todas las métricas financieras
- ✅ Gestionar productos (crear, editar, activar/desactivar)
- ✅ Ver y exportar reportes (PDF/Excel)
- ✅ Gestionar mensajes de contacto
- ✅ Ver todos los pedidos de todos los clientes
- ✅ Todo lo que puede hacer un empleado

**Responsabilidades:**
- 📊 Analizar rendimiento del negocio
- 📦 Mantener catálogo actualizado
- 💬 Responder mensajes de clientes
- 📈 Tomar decisiones basadas en datos

---

## 🛍️ Flujo del Cliente

### 1. Descubrimiento y Navegación

```
┌─────────────────┐
│  Cliente llega  │
│   al sitio web  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Página Home    │
│  - Banner       │
│  - Destacados   │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Navegar Menu   │
│  - Ver productos│
│  - Filtrar      │
│  - Leer detalles│
└─────────────────┘
```

**Acciones disponibles:**
- Ver página "Sobre Nosotros" (historia, misión, valores)
- Explorar menú completo de productos
- Leer promociones activas
- Contactar a través del formulario

### 2. Proceso de Compra

```
┌─────────────────┐
│ Seleccionar     │
│ Producto        │
└────────┬────────┘
         │
         ▼
┌─────────────────┐     ┌──────────────┐
│ Agregar al      │────▶│ Seguir       │
│ Carrito         │     │ Comprando    │
└────────┬────────┘     └──────────────┘
         │
         ▼
┌────────────────────────┐
│ Ver Carrito            │
│ - Revisar items        │
│ - Actualizar cantidad  │
│ - Eliminar items       │
│ - Eliminar extras (×)  │
│ - Consolidación auto   │
└───────────┬────────────┘
         │
         ▼
┌─────────────────┐
│ Proceder al     │
│ Checkout        │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────────┐
│ Formulario de Checkout              │
│                                     │
│ 1. Datos del Cliente                │
│    - Nombre                         │
│    - Email                          │
│    - Teléfono                       │
│                                     │
│ 2. Tipo de Entrega                  │
│    ◉ A Domicilio (+$3,000)         │
│    ◯ Retiro en Tienda (Gratis)     │ 
│                                     │
│ 3. Dirección (si delivery)          │
│                                     │
│ 4. Método de Pago                   │
│    ◉ Tarjeta                       │
│    ◯ Efectivo                      │
│    ◯ Transferencia                 │
│                                     │
│ 5. Código de Descuento (opcional)   │
│    [WELCOME15] - 15% primer pedido  │
│                                     │
└─────────────────┬───────────────────┘
                  │
                  ▼
         ┌────────────────┐
         │ Validaciones   │
         │ - Email válido │
         │ - Campos llenos│
         │ - Descuento    │
         └────────┬───────┘
                  │
                  ▼
         ┌────────────────┐
         │ Guardar Pedido │
         │ en MongoDB     │
         └────────┬───────┘
                  │
                  ▼
         ┌────────────────┐
         │ Confirmación   │
         │ - Nº de Orden  │
         │ - Detalles     │
         │ - Tracking Link│
         └────────────────┘
```

**Cálculo de Total:**
```
Precio Item = Precio Base + Σ(Extras activos)
Subtotal    = Σ (Precio Item × Cantidad) de todos los items
Envío       = $3,000 si delivery | $0 si pickup
Descuento   = Subtotal × 15% (solo primer pedido)
─────────────────────────────────────────────────
TOTAL       = Subtotal + Envío - Descuento
```

**Extras Disponibles:**
- Descafeinado: +$1,000
- Extra shot de café: +$990
- Syrup Vainilla: +$990
- Syrup Chocolate: +$990

**Gestión de Extras en Carrito:**
- Toggle estilo iOS para activar/desactivar
- Botón "×" para eliminar extra de item existente
- Consolidación automática: items idénticos se fusionan al eliminar extras

### 3. Seguimiento de Pedido

```
┌─────────────────┐
│ Recibe Nº Orden │
│ ORD-XXXXX       │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Página Tracking │
│ /track-order    │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────┐
│ Línea de Tiempo del Pedido      │
│                                 │
│ ● Pendiente                     │
│   └─ Confirmando pedido         │
│                                 │
│ ○ En Preparación                │
│   └─ Preparando tu café...      │
│                                 │
│ ○ Listo                         │
│   └─ ¡Tu pedido está listo!     │
│                                 │
│ ○ Entregado                     │
│   └─ ¡Disfruta tu café!         │
│                                 │
│ [Actualización automática 5s]   │
└─────────────────────────────────┘
```

**Estados del Pedido:**
| Estado | Color | Descripción | Visible para Cliente |
|--------|-------|-------------|---------------------|
| `pending` | 🔵 Info | Pedido recibido, pendiente de confirmación | ✅ Sí |
| `preparing` | 🟡 Warning | En preparación por el equipo | ✅ Sí |
| `ready` | 🟢 Success | Listo para entrega/retiro | ✅ Sí |
| `delivered` | 🟢 Success | Entregado al cliente | ✅ Sí |

### 4. Post-Compra

**Cliente registrado:**
- Puede ver historial completo en `/order-history`
- Ve todos sus pedidos ordenados por fecha
- Puede rastrear cualquier pedido anterior

**Cliente sin cuenta:**
- Debe guardar número de orden
- Puede rastrear con el número en cualquier momento

---

## 👨‍🍳 Flujo del Empleado

### Panel de Pedidos (`/employee/orders`)

```
┌──────────────────────────────────────┐
│     🔔 Panel de Pedidos              │
│                                      │
│  [Filtros: Todos | Pendientes | ... ]│
│                                      │
├──────────────────────────────────────┤
│                                      │
│  📦 Pedido #ORD-6923938C4EF5A        │
│  Estado: Pendiente                   │
│  Cliente: Juan Pérez                 │
│  Tipo: A Domicilio                   │
│  Total: $6,200                       │
│                                      │
│  Items:                              │
│  • Café Americano (x2)               │
│  • Cappuccino (x1)                   │
│                                      │
│  [Marcar como En Preparación]        │
│                                      │
├──────────────────────────────────────┤
│                                      │
│  📦 Pedido #ORD-6923945D3FA2B        │
│  Estado: En Preparación              │
│  Cliente: María González             │
│  Tipo: Retiro en Tienda              │
│  Total: $3,500                       │
│                                      │
│  Items:                              │
│  • Latte (x1)                        │
│  • Croissant (x2)                    │
│                                      │
│  [Marcar como Listo]                 │
│                                      │
└──────────────────────────────────────┘
```

### Acciones del Empleado

```
┌─────────────────┐
│ Pedido Pendiente│
└────────┬────────┘
         │
         │ [Empleado revisa]
         ▼
┌─────────────────┐
│ Iniciar         │
│ Preparación     │◀─── Click "Marcar como En Preparación"
└────────┬────────┘
         │
         │ [Cliente ve actualización en tracking]
         ▼
┌─────────────────┐
│ Preparando...   │
│ - Hacer café    │
│ - Empacar       │
│ - Verificar     │
└────────┬────────┘
         │
         │ [Empleado termina]
         ▼
┌─────────────────┐
│ Marcar como     │
│ Listo           │◀─── Click "Marcar como Listo"
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────┐
│ Pedido Listo                    │
│                                 │
│ Si Delivery:                    │
│   → Espera repartidor           │
│                                 │
│ Si Pickup:                      │
│   → Cliente puede retirar       │
│                                 │
└─────────────────┬───────────────┘
                  │
                  │ [Cliente recibe/retira]
                  ▼
         ┌────────────────┐
         │ Marcar como    │
         │ Entregado      │
         └────────────────┘
```

**Métricas visibles para empleado:**
- Cantidad de pedidos pendientes
- Pedidos en preparación actual
- Tiempo promedio de preparación (estimado)

---

## 👨‍💼 Flujo del Administrador

### 1. Dashboard Principal (`/admin/dashboard`)

```
┌─────────────────────────────────────────────────┐
│              🏢 Dashboard Admin                 │
├─────────────────────────────────────────────────┤
│                                                 │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐       │
│  │💰 Total  │ │✅ Pedidos│  │⏳ Pend.  │       │
│  │ $125,400 │  │    45    │  │    8     │       │
│  └──────────┘  └──────────┘  └──────────┘       │
│                                                 │
│  ┌──────────────────────────────────────┐       │
│  │ 📊 Resumen General                   │       │
│  │ • Tasa conversión: 68%               │       │
│  │ • Ticket promedio: $2,786            │       │
│  │ • Pedidos hoy: 12                    │       │
│  └──────────────────────────────────────┘       │
│                                                 │
│  ┌──────────────────────────────────────┐       │
│  │ ⭐ Top 5 Productos                   │       │
│  │ 1. Café Americano    - $45,200       │       │
│  │ 2. Cappuccino        - $38,500       │       │
│  │ 3. Latte             - $25,300       │       │
│  │ 4. Espresso          - $18,700       │       │
│  │ 5. Mocha             - $15,400       │       │
│  └──────────────────────────────────────┘       │
│                                                 │
│  ┌──────────────────────────────────────┐       │
│  │ 📧 Mensajes Recientes (5)            │       │
│  │ • Juan P. - Consulta horarios        │       │
│  │ • María G. - Solicitud catering      │       │
│  │ • Pedro L. - Felicitaciones          │       │
│  │                                      │       │
│  │ [Ver todos los mensajes]             │       │
│  └──────────────────────────────────────┘       │
│                                                 │
│  [Ver Reportes] [Gestionar Productos]           │
│                                                 │
└─────────────────────────────────────────────────┘
```

### 2. Reportes Financieros (`/admin/reports`)

```
┌──────────────────────────────────────────────┐
│         📊 Reportes Financieros              │
├──────────────────────────────────────────────┤
│                                              │
│  Período: [Esta Semana ▼]                    │
│           [Este Mes]                         │
│           [Este Trimestre]                   │
│           [Este Año]                         │
│                                              │
├──────────────────────────────────────────────┤
│                                              │
│  📅 Período: 17/11/2025 - 23/11/2025         │
│                                              │
│  ┌──────────────────────────────┐            │
│  │ 💰 Ingresos Totales          │            │
│  │    $156,400                  │            │
│  │    ↑ 12% vs semana anterior  │            │
│  └──────────────────────────────┘            │
│                                              │
│  ┌──────────────────────────────────────┐    │
│  │ 📦 Desglose por Estado               │    │
│  │                                      │    │
│  │ Completados:  45 ███████████  65%    │    │
│  │ Pendientes:    8 ██           12%    │    │
│  │ Preparando:   12 ████         17%    │    │
│  │ Listos:        4 █             6%    │    │
│  │                                      │    │
│  │ TOTAL: 69 pedidos                    │    │
│  └──────────────────────────────────────┘    │
│                                              │
│  ┌──────────────────────────────────────┐    │
│  │ 📈 Ingresos por Mes                  │    │
│  │                                      │    │
│  │ Enero    ████████ $45,200            │    │
│  │ Febrero  █████████ $52,300           │    │
│  │ Marzo    ██████████ $58,100          │    │
│  │ ...                                  │    │
│  └──────────────────────────────────────┘    │
│                                              │
│  [📥 Exportar PDF] [📥 Exportar Excel]      │
│                                              │
└──────────────────────────────────────────────┘
```

**Métricas calculadas:**
- Ingresos totales del período
- Cantidad de pedidos por estado
- Porcentaje de conversión
- Ticket promedio
- Productos más vendidos
- Comparación con período anterior

### 3. Gestión de Productos (`/products`)

```
┌────────────────────────────────────────────────┐
│         📦 Gestión de Productos                │
├────────────────────────────────────────────────┤
│                                                │
│  [+ Crear Nuevo Producto]                      │
│                                                │
├────────────────────────────────────────────────┤
│  Producto         │ Precio  │ Estado │Acciones │
├────────────────────────────────────────────────┤
│  ☕ Americano     │ $2,500  │ 🟢 Activo│      │
│  [Editar] [Desactivar]                         │
│                                                │
│  ☕ Cappuccino    │ $3,200  │ 🟢 Activo│      │
│  [Editar] [Desactivar]                         │
│                                                │
│  ☕ Espresso      │ $2,000  │ 🔴 Inactivo│    │
│  [Editar] [Activar]                            │
│                                                │
└────────────────────────────────────────────────┘
```

**Soft Delete Implementation:**
- Productos desactivados NO se muestran en el menú público
- Productos desactivados SI se muestran en panel admin (con indicador)
- Pedidos anteriores mantienen referencia a productos desactivados
- Se puede reactivar en cualquier momento

### 4. Mensajes de Contacto (`/admin/messages`)

```
┌──────────────────────────────────────────────┐
│         📧 Mensajes de Contacto              │
├──────────────────────────────────────────────┤
│                                              │
│  📨 Juan Pérez (juan@email.com)              │
│  📅 23/11/2025 14:35                         │
│  🆕 Nuevo                                    │
│                                              │
│  "Hola, quisiera saber los horarios de       │
│   atención para el fin de semana..."         │
│                                              │
│  [✓ Marcar como Leído] [✉️ juan@email.com]  │
│                                              │
├──────────────────────────────────────────────┤
│                                              │
│  📨 María González (maria@email.com)         │
│  📅 22/11/2025 10:20                         │
│  ✅ Leído                                    │
│                                              │
│  "¿Hacen servicio de catering para eventos?  │
│   Necesitaría para 50 personas..."           │
│                                              │
│  [✉️ maria@email.com]                        │
│                                              │
└──────────────────────────────────────────────┘
```

---

## 🔄 Ciclo de Vida del Pedido

### Estados y Transiciones

```
     ┌───────────┐
     │ PENDING   │ ← Pedido creado por cliente
     └─────┬─────┘
           │
           │ Empleado inicia preparación
           ▼
     ┌───────────┐
     │ PREPARING │ ← En preparación
     └─────┬─────┘
           │
           │ Empleado termina preparación
           ▼
     ┌───────────┐
     │   READY   │ ← Listo para entrega/retiro
     └─────┬─────┘
           │
           │ Cliente recibe/retira
           ▼
     ┌───────────┐
     │ DELIVERED │ ← Pedido completado
     └───────────┘
```

### Notificaciones y Actualizaciones

| Estado | Quién Actualiza | Notificación Cliente | Actualización UI |
|--------|-----------------|---------------------|------------------|
| `pending` | Sistema (auto) | Email confirmación | Tracking disponible |
| `preparing` | Empleado | Actualización en tracking | Timeline avanza |
| `ready` | Empleado | Actualización en tracking | Alert "¡Listo!" |
| `delivered` | Empleado | Finalización | Timeline completo |

**Actualización en Tiempo Real:**
- Polling cada 5 segundos en página tracking
- Sin recarga de página
- Actualización automática de UI
- Cambio de colores y alertas según estado

---

## 💰 Sistema de Descuentos

### Código: WELCOME15

**Características:**
```
┌────────────────────────────────────────┐
│  🎁 Código de Descuento WELCOME15      │
│                                        │
│  ✅ Requisitos:                        │
│  • Cliente de primer pedido            │
│  • Email sin pedidos previos           │
│  • Código exacto: "WELCOME15"          │
│                                        │
│  💰 Beneficio:                         │
│  • 15% de descuento                    │
│  • Aplicado sobre subtotal             │
│  • No incluye costo de envío           │
│                                        │
│  ❌ Restricciones:                     │
│  • Solo un uso por email               │
│  • No acumulable con otras ofertas     │
│  • Validación automática en checkout   │
│                                        │
└────────────────────────────────────────┘
```

### Validación del Descuento

```javascript
// Frontend (checkout.php)
1. Cliente ingresa email
2. AJAX verifica en servidor: /cart/check-email
3. Servidor consulta MongoDB: orden por email
4. Responde: { eligible: true/false }
5. Habilita/deshabilita campo de descuento

// Backend (CartController)
1. Cliente envía pedido con código WELCOME15
2. Valida código exacto
3. Consulta MongoDB: pedidos del email
4. Si empty() → aplica descuento
5. Si existe pedido → ignora código
6. Guarda orden con/sin descuento
```

### Ejemplo de Cálculo

```
Carrito:
• Americano x2  = $5,000
• Cappuccino x1 = $3,200
─────────────────────────
Subtotal        = $8,200

Envío (delivery) = $3,000

Descuento WELCOME15:
$8,200 × 15% = -$1,230
─────────────────────────
TOTAL = $8,200 + $3,000 - $1,230
TOTAL = $9,970
```

---

## 📦 Gestión de Productos

### Flujo de Creación

```
┌─────────────────────┐
│ Admin accede a      │
│ /products/create    │
└──────────┬──────────┘
           │
           ▼
┌──────────────────────────────────┐
│ Formulario Nuevo Producto        │
│                                  │
│ Nombre:        [_______________] │
│ Descripción:   [_______________] │
│ Precio:        [_______________] │
│ Tamaño:        [S/M/L/XL      ▼] │
│ Imagen URL:    [_______________] │
│                (usar Postimg)    │
│ Ícono:         [bi-cup-hot    ▼] │
│                                  │
│ ☑ Activo                         │
│ ☑ Nuevo                          │
│                                  │
│ [Guardar Producto]               │
└──────────────────────────────────┘
           │
           ▼
┌─────────────────────┐
│ ProductsController  │
│ valida y guarda en  │
│ MongoDB (products)  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Producto visible en │
│ menú para clientes  │
└─────────────────────┘
```

### Soft Delete (Activar/Desactivar)

**Ventajas del Soft Delete:**
1. ✅ Productos temporalmente fuera de stock
2. ✅ Productos estacionales
3. ✅ Mantiene historial de pedidos
4. ✅ Se puede reactivar fácilmente
5. ✅ No rompe referencias en pedidos anteriores

```
Estado: active = true  → Cliente VE en menú
Estado: active = false → Cliente NO ve, Admin SÍ ve

Product.getAll()         → Filtra active: true
Product.getAllForAdmin() → Muestra todos
```

### Imágenes de Productos

**Sistema de URLs externas:**
- Se usa Postimg u otro servicio de hosting
- Admin ingresa URL completa
- No se almacenan imágenes en servidor
- Reduce tamaño del proyecto Docker

```
Ejemplo URL:
https://i.postimg.cc/ABC123XYZ/cafe-americano.jpg
```

---

## 💬 Sistema de Comunicación

### Flujo de Contacto

```
┌─────────────────────┐
│ Cliente visita      │
│ /contact            │
└──────────┬──────────┘
           │
           ▼
┌──────────────────────────────────┐
│ Formulario de Contacto           │
│                                  │
│ Nombre:   [___________________]  │
│ Email:    [___________________]  │
│ Mensaje:  [___________________]  │
│           [___________________]  │
│           [___________________]  │
│                                  │
│ [✉️ Enviar Mensaje]              │
└──────────────────────────────────┘
           │
           │ POST /contact/send
           ▼
┌─────────────────────┐
│ ContactController   │
│ • Valida campos     │
│ • Valida email      │
│ • Guarda en MongoDB │
│   colección:        │
│   'contactos'       │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ ✅ Alerta de éxito  │
│ (auto-cierre 5s)    │
│                     │
│ "¡Mensaje enviado!  │
│ Nos contactaremos"  │
└─────────────────────┘
```

### Gestión de Mensajes (Admin)

```
Dashboard:
• Muestra últimos 5 mensajes
• Badge "Nuevo" en no leídos
• Link "Ver todos"

/admin/messages:
• Lista completa de mensajes
• Ordenados por fecha DESC
• Filtro leído/no leído
• Email clickable (mailto:)
• Botón "Marcar como leído"

Almacenamiento:
{
  nombre: String,
  email: String,
  mensaje: String,
  fecha: UTCDateTime,
  leido: Boolean
}
```

---

## 📊 Métricas y KPIs del Negocio

### Dashboard Principal

| Métrica | Cálculo | Uso |
|---------|---------|-----|
| Ingresos Totales | Σ pedidos completados | Salud financiera |
| Pedidos Completados | COUNT(status: 'delivered') | Volumen de ventas |
| Pedidos Pendientes | COUNT(status: 'pending') | Carga operativa |
| Ticket Promedio | Ingresos / Pedidos | Valor por cliente |
| Tasa de Conversión | (Completados / Total) × 100 | Eficiencia |

### Reportes Financieros

**Por Período:**
- Semanal: Últimos 7 días
- Mensual: Mes actual
- Trimestral: 3 meses
- Anual: 12 meses

**Gráficos:**
- Ingresos por mes (barra)
- Pedidos por estado (pie/donut)
- Productos top 5 (ranking)

**Exportación:**
- PDF: Reporte profesional con DomPDF
- Excel: Datos tabulados con PhpSpreadsheet

---

## 🔐 Reglas de Negocio

### Pedidos
1. **Un pedido puede tener múltiples items** del mismo o diferentes productos
2. **El subtotal no incluye envío** pero sí se descuenta el código promocional
3. **El costo de envío es fijo** ($3,000) para delivery
4. **Los estados son secuenciales** (no se puede saltar estados)
5. **Un pedido completado no se puede modificar**

### Descuentos
1. **Solo primer pedido por email** (validación estricta)
2. **Código case-sensitive** ("WELCOME15" exacto)
3. **Descuento sobre subtotal** (antes de agregar envío)
4. **No acumulable** con futuras promociones

### Productos
1. **Productos desactivados no se eliminan** (soft delete)
2. **URLs de imágenes deben ser válidas** (validación de formato)
3. **Precio mínimo $100** (validación)
4. **Nombre único recomendado** (no forzado)

### Usuarios
1. **Email único por cuenta**
2. **Contraseña mínimo 6 caracteres**
3. **Rol asignado en creación** (no cambia)
4. **Clientes pueden comprar sin cuenta** (guest checkout)

---

## 🚀 Optimizaciones del Negocio

### Performance
- ✅ Caché de productos activos (reduce queries)
- ✅ Índices en colecciones MongoDB
- ✅ Polling inteligente (pausa en tab oculto)
- ✅ Imágenes externas (reduce peso del servidor)

### Experiencia de Usuario
- ✅ Tracking en tiempo real sin recargar
- ✅ Alerts auto-desaparecen (no molestan)
- ✅ Validación de descuento en vivo (feedback inmediato)
- ✅ Formularios con validación client-side

### Operaciones
- ✅ Panel empleado simple y eficiente
- ✅ Estados claros con colores
- ✅ Reportes exportables (decisiones basadas en datos)
- ✅ Soft delete (recuperación fácil)

---

## 📈 Oportunidades de Mejora

### Corto Plazo
- [ ] Notificaciones push para clientes
- [ ] Chat en vivo para soporte
- [ ] Sistema de reseñas de productos
- [ ] Programa de lealtad (puntos)

### Mediano Plazo
- [ ] Integración con pasarelas de pago reales
- [ ] App móvil nativa
- [ ] Gestión de inventario real
- [ ] Sistema de cupones avanzado

### Largo Plazo
- [ ] IA para recomendaciones personalizadas
- [ ] Análisis predictivo de demanda
- [ ] Integración con ERP
- [ ] Multi-tienda y franquicias

---

**Flujo de negocio diseñado para escalabilidad y experiencia de usuario óptima** 🚀☕
