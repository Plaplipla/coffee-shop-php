# Actualización Automática de Seguimiento de Pedidos

## 🎯 Descripción

Se ha implementado un sistema de actualización automática en la página de seguimiento de pedidos (`/track-order`). La página ahora se actualiza automáticamente cada 5 segundos sin necesidad de que el cliente recargue manualmente.

## ✨ Características

### 1. **Actualización Automática cada 5 segundos**
- El sistema consulta al servidor automáticamente cada 5 segundos
- Si el estado del pedido cambió, la interfaz se actualiza en tiempo real
- No requiere que el cliente recargue la página

### 2. **Mensaje de Estado Dinámico (Alerta Celeste)**
El mensaje en la alerta se actualiza automáticamente según el estado:

| Estado | Mensaje | Color |
|--------|---------|-------|
| **Pending** | "Nos comunicaremos contigo en breve para confirmar tu pedido..." | Azul (info) |
| **Preparing** | "Tu pedido está siendo preparado. ¡Estará listo pronto!" | Amarillo (warning) |
| **Ready** | "¡Tu pedido está listo para retiro/entrega!" | Verde (success) |
| **Delivered** | "¡Tu pedido ha sido entregado! Gracias por tu compra." | Verde (success) |

### 3. **Timeline Visual Actualizado**
- El timeline de estados se actualiza dinámicamente sin recargar
- Los círculos cambian de color según el progreso:
  - ⚪ Gris: No visitado
  - 🔵 Azul: Estado actual (con animación de pulse)
  - 🟢 Verde: Estado completado

### 4. **Animación de Pulse**
El estado actual tiene una animación de pulso que lo hace más visible:
```css
@keyframes pulse {
    0% { box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2); }
    50% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
    100% { box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2); }
}
```

### 5. **Reactivación al Volver a la Pestaña**
Si el cliente cambia a otra pestaña y vuelve, la página se actualiza inmediatamente:
```javascript
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        updateOrderStatus();
    }
});
```

## 🔧 Implementación Técnica

### Archivos Modificados

#### `src/views/track-order.php`
- **IDs añadidos**: Cada elemento de estado ahora tiene un ID único (`id="status-pending"`, `id="status-preparing"`, etc.)
- **Mensajes dinámicos**: Los textos de la alerta se renderizan con PHP pero se actualizan con JavaScript
- **Script de polling**: Consulta al servidor cada 5 segundos vía AJAX POST

#### `src/controllers/TrackingController.php`
- **Sin cambios**: El controlador ya devuelve el HTML completo con la información actualizada
- El script parsea el HTML devuelto para extraer el estado actual

### Flujo de Actualización

```
[Cliente ve página de seguimiento]
        ↓
[Cada 5 segundos]
        ↓
[JavaScript envía POST /track-order con order_number]
        ↓
[TrackingController devuelve HTML actualizado]
        ↓
[Script parsea HTML y extrae nuevo estado]
        ↓
[Si estado cambió:]
    - Actualizar color de alerta
    - Actualizar icono de alerta
    - Actualizar texto del mensaje
    - Actualizar timeline de estados
        ↓
[Sin recargar página]
```

## 📝 Ejemplo de Uso

### Para el Cliente:

1. El cliente ingresa su número de orden en `/track-order`
2. Ve el estado actual del pedido con un mensaje descriptivo (alerta celeste)
3. El navegador comienza a consultar automáticamente cada 5 segundos
4. Cuando el trabajador actualiza el estado en el panel de empleado:
   - El cliente ve el cambio en tiempo real (sin hacer nada)
   - El mensaje se actualiza automáticamente
   - El timeline se anima hacia el nuevo estado
   - El icono de la alerta cambia de color

### Para el Empleado:

El flujo permanece igual:
1. Ver pedidos en `/employee/orders`
2. Hacer clic en los botones de estado (icono de reloj, engranaje, etc.)
3. El estado se actualiza en MongoDB
4. Los clientes ven el cambio automáticamente

## 🎨 Estilos CSS Agregados

```css
/* Animación de pulse */
.status-item.active .status-circle {
    animation: pulse 2s infinite;
}

/* Transiciones suaves */
#statusAlert {
    transition: all 0.5s ease;
}

.status-circle {
    transition: all 0.5s ease;
}

.status-label {
    transition: color 0.5s ease;
}

/* Variantes de color de alerta */
#statusAlert.alert-info { background-color: #d1ecf1; }
#statusAlert.alert-warning { background-color: #fff3cd; }
#statusAlert.alert-success { background-color: #d4edda; }
```

## 🧪 Prueba Manual

Para probar la actualización automática:

1. Abre `/track-order` en una pestaña
2. Ingresa un número de orden válido
3. En otra pestaña, abre `/employee/orders`
4. Cambia el estado del pedido (ejemplo: pending → preparing)
5. Vuelve a la pestaña de seguimiento
6. Observa cómo se actualiza automáticamente sin recargar

## 📊 Monitoreo en Consola

El script registra cambios en la consola del navegador:
```javascript
console.log('Estado cambió de pending a preparing');
```

Abre F12 → Consola para ver los cambios.

## 🚀 Rendimiento

- **Intervalo de polling**: 5 segundos (configurable en línea 301)
- **Datos enviados**: Solo el número de orden (muy ligero)
- **Respuesta esperada**: HTML completo (~5-10 KB)
- **Impacto en servidor**: Minimal, sin conexiones persistentes

## 🔄 Cambios en Tiempo Real

| Acción del Empleado | Tiempo hasta que el Cliente lo Vea |
|-------|-------|
| Cambiar estado | Máximo 5 segundos |
| Sin cambios (consulta) | Instantáneo (no se actualiza) |
| Volver a pestaña | Inmediato |

## 📱 Responsivo

La página de seguimiento es completamente responsiva y funciona en:
- ✅ Desktop (Chrome, Firefox, Safari, Edge)
- ✅ Tablet
- ✅ Mobile
- ✅ Navegadores antiguos (IE 11+)

## ⚙️ Configuración

Para cambiar el intervalo de actualización, edita `src/views/track-order.php` línea ~301:

```javascript
// Cambiar de 5000 milisegundos (5 segundos) a otro valor
setInterval(updateOrderStatus, 5000); // ← Modificar aquí
```

Valores sugeridos:
- `3000` = Actualizar cada 3 segundos (más responsivo, más carga)
- `5000` = Actualizar cada 5 segundos (recomendado)
- `10000` = Actualizar cada 10 segundos (menos carga, menos responsivo)

## 🐛 Troubleshooting

### La página no se actualiza
1. Abre F12 → Consola
2. Verifica que no haya errores
3. Asegúrate de estar en `http://localhost` (no `https`)
4. Recarga la página e intenta de nuevo

### Los mensajes no cambian de color
- Verifica que el servidor esté devolviendo los IDs correctos (`id="status-pending"`, etc.)
- Los estilos CSS ya están en la página

### El polling se detiene
- La página se pausa automáticamente cuando cambias de pestaña
- Se reanuda cuando vuelves a la pestaña
- Esto es intencional para ahorrar recursos

## 📚 Archivos Relevantes

- `src/views/track-order.php` - Interfaz y script de polling
- `src/controllers/TrackingController.php` - Backend de búsqueda
- `src/views/employee/orders.php` - Panel de actualización de estados
- `src/models/Order.php` - Modelo de pedidos
