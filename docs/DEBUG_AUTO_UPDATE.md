# 🔧 TROUBLESHOOTING: Auto-Update No Funciona

## 🔍 Paso 1: Verifica la Consola del Navegador

1. Abre `http://localhost/track-order`
2. Ingresa un número de orden
3. Abre **F12** (Developer Tools)
4. Ve a la pestaña **Console**

Deberías ver estos mensajes:

```
✓ Iniciando polling para orden: ORD-20251118-12345
✓ Estado inicial: pending
✓ Polling iniciado - Actualizando cada 5 segundos
```

## 🔍 Paso 2: Espera 5 Segundos y Busca Estos Mensajes

Cada 5 segundos deberías ver:

```
📍 Respuesta del servidor recibida
✓ Nuevo estado detectado: pending
⏳ Sin cambios, esperando...
```

O si hubo cambio:

```
✅ Estado cambió de "pending" a "preparing"
```

## ❌ Problema: "Iniciando polling para orden: " (vacío)

**Causa:** El número de orden no se está pasando correctamente al JavaScript.

**Solución:**

1. Ve a la línea donde dice `const orderNumber = '<?php echo... ?>';`
2. Abre la consola (F12)
3. Escribe: `orderNumber`
4. Si ves `""` (vacío), es el problema

**Fix:**
```php
// En src/views/track-order.php, línea ~371
const orderNumber = '<?php echo isset($order->order_number) ? $order->order_number : ''; ?>';

// Verifica que $order->order_number existe:
// En el formulario de búsqueda, deberías ver el número de orden.
```

## ❌ Problema: "Error actualizando estado"

**Causa:** El servidor no está devolviendo HTML válido.

**Solución:**

1. Abre la consola (F12)
2. Ve a **Network**
3. Busca requests a `/track-order`
4. Haz clic en una request
5. Ve a la pestaña **Response**
6. Deberías ver HTML completo (no error 404)

**Si ves error 404:**
- El router no está configurado correctamente
- Verifica `src/public/index.php` línea `case 'track-order':`

## ❌ Problema: El Número de Orden Que Buscas NO Existe

**Si ves el número en la tabla de empleados pero NO en track-order:**

Esto significa que el número de orden en la BD es diferente.

**Debug:**

1. Ve a `/employee/orders`
2. Abre **F12 → Console**
3. Copia un número de orden que ves en la tabla
4. Ve a `/track-order`
5. Pega ese número exacto
6. Debería funcionar

**Si aun así no funciona:**

```php
// En src/controllers/TrackingController.php
// Agrega este debug:

foreach ($allOrders as $o) {
    $oNum = isset($o['order_number']) ? $o['order_number'] : (isset($o->order_number) ? $o->order_number : '');
    error_log('Orden en DB: ' . $oNum); // ← Esto mostrará todos los números
    if ($oNum === $orderNumber) {
        // encontrado...
    }
}

// Luego mira los logs del servidor (en la carpeta logs o terminal)
```

## ✅ Verificación Completa (Paso a Paso)

### Paso 1: Crear una orden nueva
```
1. Abre http://localhost/menu
2. Agrega productos al carrito
3. Compra (checkout)
4. Copia el número de orden (se muestra en "Order Confirmation")
```

### Paso 2: Buscar en track-order
```
1. Abre http://localhost/track-order
2. Pega el número exacto
3. Haz clic en "Buscar"
4. Abre F12 → Console
5. Deberías ver "Iniciando polling para orden: ORD-..."
```

### Paso 3: Cambiar estado en employee
```
1. Abre http://localhost/employee/orders
2. Busca la misma orden
3. Cambia el estado (click en ⏱️, ⚙️, ✓, o 📦)
```

### Paso 4: Observar cambio automático
```
1. Vuelve a la pestaña de track-order
2. En <5 segundos deberías ver:
   - Color cambiar
   - Mensaje cambiar
   - Timeline animarse
   - En F12 → Console: "Estado cambió de..."
```

## 📊 Script de Debug Manual

Si quieres hacer debugging manualmente, abre F12 → Console y ejecuta:

```javascript
// Ver número de orden
console.log('Orden: ' + orderNumber);

// Forzar actualización inmediata
updateOrderStatus();

// Ver último estado conocido
console.log('Último estado: ' + lastStatus);

// Ver la función de status UI
console.log(updateStatusUI);
```

## 🔗 Verificar que el servidor está respondiendo

Abre F12 → Network y:
1. Busca un pedido
2. Espera 5 segundos
3. Deberías ver una request POST a `/track-order`
4. Status debería ser `200 OK`
5. Response debería contener HTML (`<div class="status-item">`)

## 🔄 Si nada funciona:

### Opción 1: Recarga todo
```bash
cd coffee-shop-main
./stop.sh
./start.sh
```

### Opción 2: Limpia el cache
- F12 → Application → Clear Storage
- Recarga página (Ctrl+Shift+R)

### Opción 3: Verifica logs
```bash
# Ver logs del servidor
tail -f logs/* 2>/dev/null || echo "No hay logs"
```

## 📝 Resumen

| Problema | Síntoma | Solución |
|----------|---------|----------|
| Order number vacío | `orderNumber = ''` | Verifica que el número se pasa en PHP |
| Error 404 | `fetch error` | Verifica ruta `/track-order` en router |
| No detecta cambios | `Sin cambios, esperando...` | Cambia estado en employee/orders |
| Número diferente | `No encontrado` | Copia número exacto de employee/orders |
| Consola vacía | Nada aparece | Abre F12 ANTES de buscar orden |

## ✅ Checklist Final

```
□ Consola muestra "Iniciando polling"
□ Consola muestra "Estado inicial: pending"
□ Cada 5 seg aparecen logs nuevos
□ Cuando cambias estado, aparece "Estado cambió"
□ El UI actualiza (color, texto, timeline)
□ Sin recargar página
```

**Si todo lo anterior es OK, ¡está funcionando!** ✨

---

## 📞 Necesitas más ayuda?

1. Abre la **Consola (F12)**
2. Copia el **primer error** que ves
3. Comparte el número de orden que usas
4. Comparte qué estado intentas cambiar
5. Dime si los logs de la Consola muestran algo diferente

