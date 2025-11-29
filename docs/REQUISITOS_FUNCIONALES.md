# Requisitos Funcionales - Cafetería Aroma

**Versión:** 1.0  
**Fecha:** Noviembre 2025  
**Referencia:** IE 1.5.1

---

## Índice
1. [RF1 - Gestión de Usuarios](#rf1---gestión-de-usuarios)
2. [RF2 - Catálogo de Productos](#rf2---catálogo-de-productos)
3. [RF3 - Carrito de Compras y Proceso de Pago](#rf3---carrito-de-compras-y-proceso-de-pago)
4. [RF4 - Gestión de Inventario](#rf4---gestión-de-inventario)
5. [RF5 - Gestión de Pedidos](#rf5---gestión-de-pedidos)
6. [RF6 - Sistema de Promociones y Fidelización](#rf6---sistema-de-promociones-y-fidelización)
7. [RF7 - Panel Administrativo y Estadísticas](#rf7---panel-administrativo-y-estadísticas)

---

## RF1 - Gestión de Usuarios

### Descripción
Gestión integral del sistema de usuarios, roles y autenticación de la plataforma.

### Requisitos Específicos

#### 1.1 Administración de Roles
**Actor:** Administrador  
**Descripción:** El administrador debe poder crear, modificar y eliminar roles en el sistema.

**Roles del Sistema:**
- **Administrador** - Acceso total a todas las funciones (gestión de usuarios, productos, pedidos, reportes)
- **Empleado** - Acceso a gestión de pedidos (ver, actualizar estado) y visualización de productos
- **Cliente** - Acceso a catálogo, carrito, historial de pedidos y promociones
- **Repartidor** - Acceso a pedidos asignados para entrega (visibilidad y actualización de estado)

**Funcionalidades:**
- Crear nuevos roles con permisos personalizados
- Editar permisos de roles existentes
- Eliminar roles (solo si no tienen usuarios asociados)
- Asignar/reasignar roles a usuarios

#### 1.2 Registro de Clientes
**Actor:** Usuario no registrado  
**Descripción:** Los clientes pueden registrarse de forma segura en la plataforma.

**Requisitos:**
- Campo de correo electrónico válido (validación de formato)
- Contraseña segura (mínimo 8 caracteres, mayúsculas, números, símbolos)
- Confirmación de contraseña
- Aceptación de términos y condiciones
- Validación de correo electrónico mediante enlace de confirmación
- Almacenamiento seguro de contraseñas (hash con algoritmo bcrypt o similar)

**Validaciones:**
- Correo único en la base de datos
- Formato de correo válido
- Contraseña cumple con requisitos de seguridad

#### 1.3 Acceso como Invitado
**Actor:** Usuario sin registro  
**Descripción:** El sistema permite la compra sin registro previo.

**Funcionalidades:**
- Navegación de catálogo sin autenticación
- Acceso al carrito sin login
- Proceso de checkout completo como invitado
- Solicitud de correo electrónico al finalizar la compra
- Opción de crear cuenta después de la compra

#### 1.4 Autenticación y Sesiones
**Requisitos:**
- Inicio de sesión con correo y contraseña
- Recuperación de contraseña por correo electrónico
- Cierre de sesión
- Validación de sesión en cada solicitud
- Expiración de sesión por inactividad
- Protección contra ataques CSRF

---

## RF2 - Catálogo de Productos

### Descripción
Gestión completa del catálogo de productos disponibles en la cafetería.

### Requisitos Específicos

#### 2.1 Visualización de Productos
**Actor:** Cliente/Invitado  
**Descripción:** Los usuarios pueden visualizar todos los productos disponibles.

**Información de Producto:**
- Nombre del producto
- Descripción detallada
- Ingredientes (lista completa)
- Precio unitario
- Imagen del producto
- Disponibilidad (disponible/no disponible)
- Alérgenos (si aplica)

#### 2.2 Filtrado por Categorías
**Actor:** Cliente/Invitado  
**Descripción:** El catálogo puede filtrarse por categorías de productos.

**Categorías Disponibles:**
- Cafés (Americano, Latte, Cappuccino, Espresso, etc.)
- Bebidas Frías (Frappés, Smoothies)
- Postres (Cheesecake, Brownies, Muffins)
- Snacks (Croissants, Sandwiches)
- Bebidas Calientes (Chocolate, Té)

**Funcionalidades:**
- Filtrar por una o varias categorías
- Mostrar cantidad de productos por categoría
- Vista de lista o cuadrícula
- Ordenamiento (por nombre, precio, popularidad)

#### 2.3 Gestión de Productos (Administrador)
**Actor:** Administrador  
**Descripción:** El administrador gestiona todo el catálogo de productos.

**Operaciones:**
- **Crear:** Agregar nuevos productos con toda la información requerida
- **Leer:** Visualizar detalles de productos
- **Actualizar:** Editar nombre, descripción, precio, ingredientes, imagen, disponibilidad
- **Eliminar:** Remover productos del catálogo
- **Marcar Disponibilidad:** Indicar si un producto está disponible basado en stock

**Stock y Disponibilidad:**
- Definir stock mínimo predeterminado por producto
- Marcar como "No Disponible" cuando stock ≤ mínimo
- Actualización manual de stock
- Visualización de stock actual en panel de administración

---

## RF3 - Carrito de Compras y Proceso de Pago

### Descripción
Gestión del carrito de compras y procesamiento seguro de pagos en línea.

### Requisitos Específicos

#### 3.1 Carrito de Compras
**Actor:** Cliente/Invitado  
**Descripción:** Los usuarios pueden agregar, modificar y eliminar productos del carrito.

**Funcionalidades:**
- Agregar producto al carrito (con cantidad)
- Modificar cantidad de productos en el carrito
- Eliminar productos del carrito
- Calcular automáticamente subtotal y total
- Aplicar descuentos/cupones
- Persistencia del carrito (sesión o base de datos)
- Validar disponibilidad de productos antes de checkout
- Agregar extras opcionales estilo toggle (descafeinado, extra shot, syrups)
- Eliminar extras individuales desde el carrito con botón ×
- Consolidación automática de items cuando, tras eliminar extras, quedan configuraciones idénticas (suma de cantidades)

**Validaciones:**
- Cantidad mínima: 1 unidad
- Cantidad máxima: stock disponible
- Verificación de disponibilidad del producto

#### 3.2 Proceso de Pago
**Actor:** Cliente/Invitado  
**Descripción:** Sistema de pago seguro con múltiples opciones.

**Métodos de Pago:**
- **Efectivo** - Pago en tienda al retiro
- **Tarjeta de Débito/Crédito** - Integración con pasarela de pago (Stripe)
- **Stripe** - Pago en línea seguro con encriptación PCI DSS

**Flujo de Pago:**
1. Revisar carrito (cantidad, precio, disponibilidad)
2. Ingresar datos de entrega (si aplica)
3. Seleccionar método de pago
4. Confirmar y procesar pago
5. Recibir confirmación y número de orden

#### 3.3 Boleta Electrónica
**Actor:** Sistema/Cliente  
**Descripción:** Emisión automática de boleta electrónica tras pago confirmado.

**Información de Boleta:**
- Número de orden único
- Fecha y hora de compra
- Detalle de productos (cantidad, precio, subtotal)
- Subtotal y total
- Método de pago utilizado
- Datos del cliente
- QR con información de pedido (para seguimiento)
- Envío por correo electrónico

**Requisitos:**
- Generación automática al confirmar pago
- Almacenamiento en base de datos
- Acceso a boleta desde historial de pedidos
- Descarga en formato PDF

#### 3.4 Verificación de Dirección de Entrega
**Actor:** Cliente (pedido con delivery)**  
**Descripción:** Validación y normalización de la dirección antes de permitir finalizar un pedido a domicilio.

**Funcionalidades:**
- Campo de dirección libre
- Botón "Verificar dirección" que consulta servicio de geocodificación (Nominatim)
- Sugerencias alternativas y selección del mejor resultado
- Mapa (Leaflet) que muestra el punto confirmado
- Almacena latitud, longitud y dirección normalizada en el pedido
- Deshabilita el botón de finalizar pedido hasta que la dirección esté verificada

**Validaciones:**
- Dirección no vacía
- Respuesta válida del servicio (status ok)
- Coordenadas dentro de área geográfica soportada
- Solo obligatorio en pedidos con tipo "delivery"

**Excepciones:**
- Para retiro en tienda no se exige verificación

---

## RF4 - Gestión de Inventario

### Descripción
Control automático del inventario de productos con notificaciones de stock bajo.

### Requisitos Específicos

#### 4.1 Notificaciones de Stock Bajo
**Actor:** Administrador/Sistema  
**Descripción:** El sistema notifica cuando el inventario cae bajo niveles predefinidos.

**Funcionalidades:**
- Definir stock mínimo por producto
- Alertas automáticas cuando stock < mínimo
- Panel de control de productos con bajo stock
- Notificaciones por correo electrónico
- Historial de alertas

**Niveles de Alerta:**
- Crítico (< 30% del stock mínimo)
- Advertencia (entre 30% y 100% del stock mínimo)

#### 4.2 Actualización Automática de Inventario
**Actor:** Sistema  
**Descripción:** El inventario se actualiza automáticamente tras cada venta.

**Procesos:**
- Decremento de stock al confirmar pago
- Validación de stock antes de venta
- Impedir venta si no hay stock disponible
- Registro de transacciones de inventario
- Historial de movimientos (entradas y salidas)

**Validaciones:**
- Stock no puede ser negativo
- Verificación de disponibilidad en tiempo real
- Reversión de stock si pago es cancelado

---

## RF5 - Gestión de Pedidos

### Descripción
Seguimiento y gestión completa del ciclo de vida de los pedidos.

### Requisitos Específicos

#### 5.1 Estados del Pedido
**Estados Disponibles:**
- **Pendiente** - Pago confirmado, esperando procesamiento
- **En Preparación** - Siendo preparado en cocina
- **Listo** - Listo para retiro o salió en reparto
- **Entregado/En Camino** - En ruta o entregado al cliente
- **Cancelado** - Pedido cancelado por cliente o administrador

**Transiciones Válidas:**
```
Pendiente → En Preparación → Listo → En Camino → Entregado
                                ↓
                            Cancelado (en cualquier momento)
```

#### 5.2 Visualización de Pedidos
**Actor:** Empleado/Repartidor  
**Descripción:** Los empleados pueden visualizar y actualizar el estado de pedidos.

**Para Cocineros/Preparadores:**
- Lista de pedidos confirmados por hacer
- Detalles: productos, cantidad, ingredientes especiales
- Botón para marcar como "Listo"
- Filtros por estado

**Para Repartidores:**
- Pedidos asignados para entrega
- Detalles de cliente (nombre, dirección, teléfono)
- Ruta optimizada
- Actualizar estado a "En Camino" o "Entregado"
- Foto de entrega (opcional)

#### 5.3 Seguimiento de Pedidos
**Actor:** Cliente  
**Descripción:** Los clientes pueden seguir el estado de sus pedidos.

**Funcionalidades:**
- Visualizar estado actual del pedido
- Historial de cambios de estado con fecha/hora
- Hora estimada de entrega
- Notificaciones por correo/SMS cuando cambia estado
- Código de seguimiento (QR)
- Contacto con soporte desde el pedido

#### 5.4 Horario de Atención
**Actor:** Sistema  
**Descripción:** Alertas informativas fuera del horario de atención.

**Requisitos:**
- Definir horario de atención (ej: 8:00 - 20:00)
- Si pedido se realiza fuera de horario:
  - Mostrar alerta: "Tu pedido será procesado a las [hora apertura]"
  - Estimar entrega para el próximo día/horario
  - Confirmar que el cliente acepta los tiempos

#### 5.5 Tiempo Estimado de Entrega
**Cálculo:**
- Tiempo de preparación base (configurable por producto)
- Tiempo de entrega según zona
- Buffer de seguridad
- Suma total = Hora estimada

**Actualización:**
- Se actualiza cuando cambia de estado
- Se comunica al cliente en cada actualización

---

## RF6 - Sistema de Promociones y Fidelización

### Descripción
Programa de lealtad y promociones para clientes.

### Requisitos Específicos

#### 6.1 Historial de Compras
**Actor:** Cliente registrado  
**Descripción:** Los clientes pueden acceder a su historial de compras.

**Información:**
- Lista de todas las compras realizadas
- Detalles: fecha, productos, precio, estado del pedido
- Filtros: por fecha, por estado, por monto
- Opción de repetir pedido anterior
- Descarga de boletas

#### 6.2 Sistema de Puntos
**Actor:** Sistema/Cliente  
**Descripción:** Acumulación automática de puntos por cada compra.

**Política de Puntos:**
- 1 punto por cada $100 gastados (configurable)
- Mínimo 1 punto por compra
- Acumulación solo para clientes registrados
- Los puntos se asignan después de entrega confirmada

**Visualización:**
- Saldo actual de puntos en perfil
- Historial de puntos ganados/canjeados
- Equivalencia en dinero (ej: 100 puntos = $5)

#### 6.3 Cupones de Descuento
**Actor:** Cliente registrado  
**Descripción:** Cupones canjeables por descuentos.

**Tipos de Cupones:**
- **Porcentaje** - Descuento porcentual (ej: 10% off)
- **Monto Fijo** - Descuento en dinero (ej: $2,000 off)
- **Envío Gratis** - Descuento en costo de envío
- **Productos** - Descuento en categoría específica

**Validez:**
- Fecha de expiración
- Monto mínimo de compra requerido
- Usos máximos (global y por usuario)
- Combinable con otros cupones (configurable)

**Funcionalidades:**
- Aplicar cupón en carrito
- Validar antes de procesar pago
- Mostrar ahorro total
- Historial de cupones canjeados

---

## RF7 - Panel Administrativo y Estadísticas

### Descripción
Dashboard para monitoreo de negocio y generación de reportes.

### Requisitos Específicos

#### 7.1 Dashboard Principal
**Métricas Principales:**
- **Ingresos Totales** - Suma de todas las ventas completadas
- **Clientes Registrados** - Cantidad total de cuentas activas
- **Productos Populares** - Top 5 productos más vendidos
- **Pedidos Hoy** - Cantidad de pedidos del día actual
- **Pedidos Pendientes** - Cantidad de pedidos sin completar
- **Órdenes por Estado** - Desglose visual (gráfico pie/barras)

**Período de Visualización:**
- Hoy
- Última semana
- Último mes
- Último trimestre
- Último año
- Personalizado (rango de fechas)

#### 7.2 Reportes Financieros
**Contenido:**
- **Resumen Mensual:** Ingresos por mes del período seleccionado
- **Desglose por Método de Pago:** Efectivo, tarjeta, Stripe
- **Comparación de Períodos:** Variación porcentual vs período anterior/año anterior
- **Productos por Ingresos:** Cuáles generaron más ingresos
- **Productos por Cantidad Vendida:** Cuáles se vendieron más unidades

**Visualización:**
- Tablas de datos
- Gráficos (barras, líneas, pie)
- Exportación a PDF o Excel
- Filtros por período, categoría, método de pago

#### 7.3 Reportes de Clientes
**Análisis:**
- Total de clientes registrados
- Clientes nuevos en el período
- Clientes más activos (más compras)
- Clientes inactivos (sin compras en X días)
- Distribución geográfica (por dirección/zona)
- Valor promedio de compra por cliente

#### 7.4 Reportes de Productos
**Análisis:**
- Productos más vendidos (cantidad y dinero)
- Productos menos vendidos
- Evolución de ventas por producto
- Productos con bajo stock
- Margen de ganancia por producto

#### 7.5 Reportes de Operaciones
**Análisis:**
- Tiempo promedio de preparación por pedido
- Tiempo promedio de entrega
- Pedidos cancelados (cantidad y razones)
- Satisfacción del cliente (si aplica calificaciones)
- Carga de trabajo por día/hora

#### 7.6 Exportación de Reportes
**Formatos:**
- PDF - Con logo y formato profesional
- Excel (CSV) - Para análisis en hojas de cálculo
- Incluye: Período, datos, gráficos (en PDF), fecha de generación

**Opciones:**
- Descargar inmediatamente
- Enviar por correo electrónico
- Programar generación automática (diaria/semanal/mensual)

---

## Estado de Implementación

| RF | Requisito | Estado | Notas |
|---|-----------|--------|-------|
| RF1.1 | Administración de Roles | ✅ Implementado | Roles: admin, empleado, cliente, repartidor |
| RF1.2 | Registro de Clientes | ✅ Implementado | Con validación de correo |
| RF1.3 | Acceso como Invitado | ✅ Implementado | Sin restricciones en catálogo/carrito |
| RF1.4 | Autenticación y Sesiones | ✅ Implementado | Con validación CSRF |
| RF2.1 | Visualización de Productos | ✅ Implementado | Con imagen, descripción, ingredientes |
| RF2.2 | Filtrado por Categorías | ✅ Implementado | Múltiples filtros simultáneos |
| RF2.3 | Gestión de Productos | ✅ Implementado | CRUD completo para admin |
| RF3.1 | Carrito de Compras | ✅ Implementado | Persistencia en sesión/BD |
| RF3.2 | Proceso de Pago | ✅ Implementado | Stripe, tarjeta, efectivo |
| RF3.3 | Boleta Electrónica | ✅ Implementado | PDF por correo, con QR |
| RF3.4 | Verificación Dirección Delivery | ✅ Implementado | Geocodificación + mapa + bloqueo hasta verificación |
| RF4.1 | Notificaciones Stock Bajo | ⚠️ Parcial | Alertas en admin, falta email |
| RF4.2 | Actualización Automática Inventario | ✅ Implementado | Al confirmar pago |
| RF5.1 | Estados del Pedido | ✅ Implementado | 5 estados + cancelación |
| RF5.2 | Visualización de Pedidos | ✅ Implementado | Panel empleado + repartidor |
| RF5.3 | Seguimiento de Pedidos | ✅ Implementado | Con QR y historial |
| RF5.4 | Horario de Atención | ⚠️ Parcial | Alerta básica, falta configuración |
| RF5.5 | Tiempo Estimado Entrega | ✅ Implementado | Cálculo automático |
| RF6.1 | Historial de Compras | ✅ Implementado | Con filtros |
| RF6.2 | Sistema de Puntos | 🔴 No implementado | Pendiente de desarrollo |
| RF6.3 | Cupones de Descuento | ✅ Implementado | Integrados en proceso de pago (Stripe) |
| RF7.1 | Dashboard Principal | ✅ Implementado | Con múltiples períodos |
| RF7.2 | Reportes Financieros | ✅ Implementado | Con exportación PDF/Excel |
| RF7.3 | Reportes de Clientes | ⚠️ Parcial | Básico, sin análisis profundo |
| RF7.4 | Reportes de Productos | ✅ Implementado | Top 5 más vendidos |
| RF7.5 | Reportes de Operaciones | ⚠️ Parcial | Datos básicos |
| RF7.6 | Exportación de Reportes | ✅ Implementado | PDF y Excel |

**Leyenda:**
- ✅ Implementado completamente
- ⚠️ Implementado parcialmente
- 🔴 No implementado

---

## Notas Técnicas

### Tecnologías Utilizadas
- **Backend:** PHP 7.4+
- **Base de Datos:** MongoDB
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Pagos:** Stripe API (REST)
- **Exportación:** DOMPDF (PDF), CSV nativo

### Arquitectura
- Patrón MVC (Model-View-Controller)
- Separación de responsabilidades
- Validación en cliente y servidor
- Manejo de errores con excepciones

### Seguridad
- Contraseñas hasheadas (bcrypt)
- Validación CSRF
- Sanitización de inputs
- Encriptación de datos sensibles
- Autenticación de sesiones

---

**Documento preparado por:** Sistema de Documentación  
**Última actualización:** 24 de Noviembre de 2025
