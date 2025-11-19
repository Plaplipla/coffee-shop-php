# Módulo Administrador - Guía Completa

## 📊 Descripción General

El módulo administrador proporciona un dashboard completo con monitoreo financiero, generación de reportes detallados y exportación de datos en múltiples formatos (PDF, Excel).

## 🎯 Características Implementadas

### 1. **Dashboard Administrativo** (`/admin/dashboard`)

**Ubicación:** `src/views/admin/dashboard.php`

Pantalla principal con:
- **Barra lateral de navegación:** Links a Dashboard, Reportes Detallados, Órdenes Activas, Gestión de Productos
- **5 Tarjetas de Métricas:**
  - Total de Ventas ($)
  - Total de Órdenes
  - Órdenes Completadas
  - Órdenes Pendientes
  - Valor Promedio por Orden
- **Lista de Productos Top 5:** Ranking de productos más vendidos con cantidades
- **Botones de Acción Rápida:** Enlaces a reportes, órdenes activas y gestión de productos

**Estilos:** Tema café con gradientes marrones (#8B4513) y dorados (#d4af37), tarjetas responsive, efectos hover

### 2. **Reportes Financieros Detallados** (`/admin/reports`)

**Ubicación:** `src/views/admin/reports.php`

Características:
- **Selector de Período:** Botones para seleccionar semana, mes, trimestre o año
- **Resumen Ejecutivo:** Ingresos totales, total de pedidos, consumo promedio
- **Tabla: Pedidos por Estado**
  - Estados: Pendiente, En Preparación, Listo, Entregado
  - Cantidad y porcentaje de cada estado
  - Gráfica de barras visual integrada

- **Tabla: Ingresos Mensuales**
  - Ingresos por mes
  - Gráfica de barras comparativa
  - Identifica patrones estacionales

- **Formulario de Exportación:**
  - Selector de formato (Excel CSV o PDF HTML)
  - Selector de período (semana, mes, trimestre, año)
  - Botón de descarga

**Estilos:** Consistente con dashboard, tablas interactivas, gráficas visuales integradas

### 3. **Controlador Administrativo** (`AdminController.php`)

**Ubicación:** `src/controllers/AdminController.php`

**Métodos Públicos:**

```php
__construct()              // Inicializa modelos y valida acceso admin
checkAccess()             // Verifica si el usuario es administrador
dashboard()               // Renderiza dashboard con métricas generales
reports()                 // Renderiza reporte detallado con período dinámico
export()                  // Maneja exportación a PDF o Excel
```

**Métodos Privados (Cálculos y Procesamiento):**

```php
getGeneralMetrics()           // Calcula métricas del dashboard
generateDetailedReports()     // Genera reportes por período
getStartDate($period)         // Obtiene fecha de inicio según período
exportToExcel($reports)       // Exporta a CSV con UTF-8 BOM
exportToPDF($reports)         // Exporta a HTML imprimible
generatePDFHTML()             // Genera HTML estilizado para PDF
convertToObject()             // Convierte arrays a objetos (MongoDB)
getStatusLabel()              // Traduce estados de órdenes
calculateTotalRevenue()       // Suma ingresos totales
```

### 4. **Métricas Generadas**

#### Dashboard (Generales):
- **total_sales:** Suma de todos los montos de órdenes
- **completed_orders:** Cantidad de órdenes entregadas
- **pending_orders:** Cantidad de órdenes pendientes
- **total_orders:** Total de órdenes en la base de datos
- **top_products:** Array con los 5 productos más vendidos (nombre => cantidad)
- **average_order_value:** Promedio de gasto por orden

#### Reportes (Por Período):
- **total_revenue:** Ingresos totales del período
- **orders_by_status:** Desglose de órdenes por estado (pending, preparing, ready, delivered)
- **monthly_revenue:** Ingresos agrupados por mes (Y-m format)
- **average_consumption:** Promedio de items por orden en el período

### 5. **Filtrado por Período**

**Opciones Disponibles:**
- **week (Esta Semana):** Últimos 7 días
- **month (Este Mes):** Primero del mes actual a hoy
- **quarter (Este Trimestre):** Primero del trimestre actual a hoy
- **year (Este Año):** 1 de enero a hoy

**Implementación:**
```php
$startDate = $this->getStartDate('month');
// Compara: $order->created_at > $startDate (timestamps)
```

### 6. **Exportación de Datos**

#### **Excel (CSV)**
- **Formato:** CSV compatible con Microsoft Excel
- **Codificación:** UTF-8 con BOM (abre correctamente en Excel)
- **Contenido:** Tablas con headers y filas de datos
- **Descarga automática** con nombre: `reporte_[período]_[fecha].csv`

#### **PDF (HTML Imprimible)**
- **Formato:** HTML estilizado con CSS inline
- **Styling:** Tema café, tablas formateadas, adecuado para impresión
- **Contenido:** Reportes completos con gráficas visuales
- **Descarga automática** con nombre: `reporte_[período]_[fecha].html`

## 🔐 Control de Acceso

**Validación en Constructor:**
```php
$this->checkAccess(); // Redirige a /login si no es admin
```

**Requisito:** `$_SESSION['role'] == 'administrador'`

**Acción:** Redirige a `/login` con mensaje de error si el usuario no es administrador

## 🛣️ Rutas Configuradas

**`index.php`** - Rutas públicas (protegidas por checkAccess en el controlador):

| Ruta | Método | Controlador | Acción |
|------|--------|-------------|--------|
| `/admin/dashboard` | GET | AdminController | Mostrar dashboard |
| `/admin/reports` | GET | AdminController | Mostrar reportes |
| `/admin/export` | POST | AdminController | Descargar reporte |

**Rutas agregadas a `$publicRoutes`:**
```php
'admin/dashboard', 'admin/reports', 'admin/export'
```

## 📊 Ejemplo de Datos Mostrados

### Dashboard:
```
Total de Ventas: $1,250.50
Total de Órdenes: 47
Órdenes Completadas: 42
Órdenes Pendientes: 5
Valor Promedio: $26.60

Top Productos:
1. Cappuccino - 156 vendidos
2. Expresso - 134 vendidos
3. Latte - 112 vendidos
4. Americano - 98 vendidos
5. Macchiato - 76 vendidos
```

### Reportes:
```
Período: Este Mes

Ingresos Totales: $1,250.50
Total de Pedidos: 47
Consumo Promedio: 3.2 items

Pedidos por Estado:
- Entregado: 42 (89.4%)
- Pendiente: 5 (10.6%)

Ingresos Mensuales:
- 2024-01: $500.00
- 2024-02: $750.50
- (según período seleccionado)
```

## 🔄 Flujo de Uso

1. **Administrador accede a `/admin/dashboard`**
   - Valida rol (checkAccess)
   - Carga AdminController→dashboard()
   - Calcula métricas generales
   - Renderiza `admin/dashboard.php`

2. **Selecciona período en reports**
   - Accede a `/admin/reports?period=month`
   - Valida rol (checkAccess)
   - Calcula reportes para el período
   - Renderiza `admin/reports.php` con datos

3. **Exporta reporte**
   - Completa formulario en reports.php
   - POST a `/admin/export` con format y period
   - Valida rol (checkAccess)
   - Genera archivo (CSV o HTML)
   - Descarga automática al navegador

## 🛠️ Detalles Técnicos

### Modelo de Datos Usado:
- **Order Model:** `src/models/Order.php`
  - Método: `getAll()` - obtiene todas las órdenes
  - Estructura: Órdenes con items, total, status, fecha de creación

### Conversión de Datos MongoDB:
```php
// MongoDB retorna objetos stdClass, se convierten a arrays
$order = $this->convertToObject($order);
$items = is_array($order->items) ? $order->items : (array)$order->items;
```

### Cálculos de Ingresos:
```php
// Suma simple por período
foreach ($filteredOrders as $order) {
    $totalRevenue += $order->total ?? 0;
}
```

### Ingresos Mensuales:
```php
// Agrupa por formato Y-m (ej: "2024-01")
$monthKey = date('Y-m', $order->created_at);
$monthlyRevenue[$monthKey] += $order->total;
```

## 📁 Estructura de Archivos

```
src/
├── controllers/
│   └── AdminController.php          (350+ líneas)
├── models/
│   └── Order.php                    (existente)
├── views/
│   └── admin/
│       ├── dashboard.php            (300+ líneas)
│       └── reports.php              (350+ líneas)
└── public/
    └── index.php                    (actualizado con rutas)
```

## 🎨 Personalización

### Colores Tema Café:
```css
Marrón Principal: #8B4513
Dorado Acentos: #d4af37
Fondo Claro: #f5deb3
Fondo Oscuro: #6b3410
```

### Modificar Período de Top 5 Productos:
```php
// Línea 134 en AdminController
$topProducts = array_slice($itemsSold, 0, 5); // Cambiar 5 a otro número
```

### Modificar Períodos Disponibles:
```php
// Línea 199 en AdminController - getStartDate()
case 'week': return time() - (7 * 24 * 60 * 60); // 7 días atrás
// Agregar más casos según necesidad
```

## ✅ Validación

- ✅ Control de acceso (solo administrador)
- ✅ Cálculos de métricas funcionan correctamente
- ✅ Exportación genera archivos válidos
- ✅ Período de filtrado funciona en ambas tablas
- ✅ Diseño responsive en móvil y desktop
- ✅ Soporte para UTF-8 en exportación Excel

## 🚀 Próximas Mejoras (Opcionales)

1. **Gráficas Interactivas:** Integrar Chart.js para gráficos dinámicos
2. **Email Reports:** Enviar reportes automáticamente a email
3. **Programación:** Generar reportes en horarios específicos
4. **Multi-período:** Comparar períodos diferentes lado a lado
5. **Filtros Adicionales:** Por producto, cliente, rango de fechas custom
6. **Análisis Predictivo:** Proyecciones de ventas futuras
7. **Dashboard Mobile:** Versión optimizada para teléfonos

## 📞 Soporte

Para errores o problemas:
1. Verificar que el usuario tenga `role = 'administrador'` en la sesión
2. Revisar que las órdenes tengan campo `created_at` con timestamp
3. Comprobar que la base de datos MongoDB contiene órdenes
4. Consultar logs en servidor para detalles de errores

---

**Versión:** 1.0 | **Última Actualización:** 2024 | **Estado:** ✅ Producción
