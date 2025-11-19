# 🧪 Guía de Prueba: Actualización Automática de Seguimiento

## ✅ Prueba 1: Cambio de Estado en Tiempo Real

### Requisitos
- Servidor corriendo (`./start.sh`)
- Navegadores web (Chrome, Firefox, etc.)

### Pasos

1. **Abre navegador A (Cliente)**
   ```
   URL: http://localhost/track-order
   ```
   
2. **Ingresa un número de orden**
   - Busca en la consola o crea una orden para obtener el número
   - Ejemplo: `ORD-20251118-2791`
   - Haz click en "Buscar"
   
   Deberías ver:
   ```
   ┌─────────────────────────────┐
   │ Estado: Pendiente           │
   │ Color: Azul                 │
   │ Timeline: ⚪→🔵→⚪→⚪       │
   └─────────────────────────────┘
   ```

3. **Abre navegador B (Empleado)**
   ```
   URL: http://localhost/employee/orders
   Ingresa usuario y contraseña del empleado
   ```
   
   Deberías ver la lista de pedidos con botones de estado.

4. **En navegador B: Cambiar estado**
   - Busca el mismo número de orden
   - Haz clic en el icono ⏱️ (En Preparación)
   - El pedido se actualiza en MongoDB

5. **En navegador A: Observa cambios automáticos**
   
   **Espera máximo 5 segundos** y verifica:
   
   ✅ El color cambió a amarillo
   ```
   ┌─────────────────────────────┐
   │ Estado: En Preparación      │ ← Cambió
   │ Mensaje: "Tu pedido se      │ ← Cambió
   │ prepara..."                 │
   │ Timeline: ⚪→⚪→🔵→⚪        │ ← Cambió
   │ Color: Amarillo             │ ← Cambió
   └─────────────────────────────┘
   ```
   
   ✅ **SIN RECARGAR LA PÁGINA**

6. **Repite con otros estados**
   - Cambio a "Listo" (icono ✓)
   - Cambia a "Entregado" (icono 📦)
   - Cada cambio se refleja en ~5 segundos

---

## ✅ Prueba 2: Pausa en Otra Pestaña

### Pasos

1. **Abre navegador con seguimiento**
   ```
   http://localhost/track-order
   Ingresa orden
   ```

2. **Abre otra pestaña**
   - Gmail, YouTube, Wikipedia, etc.
   - **La página de seguimiento está oculta**

3. **En otra ventana: Cambia estado del pedido**
   - Abre `/employee/orders`
   - Cambia el estado

4. **Vuelve a la pestaña de seguimiento**
   - Haz click en la pestaña
   - Verás: **El cambio está actualizado al instante**
   - No esperaste 5 segundos completos
   
   ```
   Evento: visibilitychange detectado
   ↓
   updateOrderStatus() ejecutada inmediatamente
   ↓
   Página actualizada
   ```

---

## ✅ Prueba 3: Console Log (Debug)

### Pasos

1. **Abre navegador con seguimiento**
   ```
   http://localhost/track-order
   ```

2. **Abre DevTools**
   ```
   Atajo: F12 o Ctrl+Shift+I (Windows)
           Cmd+Option+I (Mac)
   ```

3. **Ve a la pestaña "Console"**
   ```
   ┌────────────────────────────────┐
   │ 🔍 Inspector │ Console │ Network
   │                                │
   │ (aquí aparecen los logs)      │
   └────────────────────────────────┘
   ```

4. **En otra ventana: Cambia estado del pedido**

5. **En Console: Deberías ver**
   ```
   Estado cambió de pending a preparing
   ```
   
   O si no hay cambios:
   ```
   (nada aparece en Console)
   ```

### Mensajes Esperados

```
// Cambio detectado exitoso
Estado cambió de pending a preparing

// Error en consulta
Error actualizando estado: [error details]

// Sin cambios (no aparece nada)
(silencio, esperando siguiente ciclo)
```

---

## ✅ Prueba 4: Intervalo de Polling (Avanzado)

### Pasos

1. **Abre Console (F12)**

2. **En la Console, escribe:**
   ```javascript
   // Ver estado actual
   console.log(lastStatus);
   
   // Resultado: "pending" (o el estado actual)
   ```

3. **Verifica que está consultando:**
   ```javascript
   // Ejecutar actualización manual
   updateOrderStatus();
   
   // Result: Hace una consulta inmediata sin esperar 5 seg
   ```

4. **Ver próxima consulta:**
   ```javascript
   // Tiempo de próxima consulta
   console.log('Próxima consulta en ~5 segundos');
   ```

---

## ✅ Prueba 5: Múltiples Órdenes Simultáneas

### Pasos

1. **Abre dos pestañas diferentes**
   ```
   Pestaña A: orden #1 (ORD-123)
   Pestaña B: orden #2 (ORD-456)
   ```

2. **Ambas tienen polling activo**
   - Pestaña A consulta cada 5 seg
   - Pestaña B consulta cada 5 seg
   - Independientemente

3. **Cambia estado de orden #1**
   - Pestaña A se actualiza
   - Pestaña B no se afecta (orden diferente)

4. **Cambia estado de orden #2**
   - Pestaña B se actualiza
   - Pestaña A no se afecta

---

## 📊 Matriz de Pruebas

| Prueba | Resultado Esperado | Estado |
|--------|-------------------|--------|
| Cambio automático | Se actualiza en <5seg | ✅ |
| Sin recargar página | HTML no se recarga | ✅ |
| Color alerta cambia | Azul→Amarillo→Verde | ✅ |
| Texto alerta cambia | Mensaje descriptivo | ✅ |
| Timeline actualiza | Círculos avanzan | ✅ |
| Console log muestra cambio | "Estado cambió de..." | ✅ |
| Pausa en otra pestaña | No consulta mientras oculta | ✅ |
| Reactivación inmediata | Actualiza al volver | ✅ |
| Múltiples órdenes | Cada una es independiente | ✅ |

---

## 🔧 Troubleshooting: Si No Funciona

### Problema: Página no se actualiza

**Solución 1: Verifica la consola**
```javascript
// En F12 → Console
// Deberías ver:
"Estado cambió de pending a preparing"
```

**Solución 2: Recarga la página**
```
F5 (recarga)
```

**Solución 3: Verifica que el servidor está corriendo**
```bash
./start.sh
# Deberías ver "Server running on http://localhost"
```

---

### Problema: El mensaje no cambia de color

**Solución: Verifica que IDs existan**
```javascript
// En F12 → Console, escribe:
document.getElementById('statusAlert')

// Deberías ver el elemento <div>
```

---

### Problema: El timeline no se anima

**Solución: Verifica que hay CSS**
```javascript
// En F12 → Inspector, selecciona el timeline
// En "Styles" deberías ver:
// .status-item.completed { ... }
// .status-item.active { ... }
```

---

### Problema: Error "fetch() no es una función"

**Solución: Navegador muy antiguo**
- Usa Chrome, Firefox, Safari o Edge moderno
- IE 11 requiere polifills

---

## 📈 Monitoreo del Rendimiento

### En DevTools → Network Tab

1. **Abre F12 → Network**
2. **Filtra por XHR (AJAX)**
   ```
   Deberías ver POST /track-order cada 5 seg
   ```
3. **Verifica:**
   - Status: 200 OK
   - Size: ~5-10 KB
   - Time: <100 ms

---

## 🎯 Checklist Final de Pruebas

```
□ Cambio de estado se refleja automáticamente
□ No se recarga la página
□ Colores cambian correctamente
□ Mensajes cambian correctamente
□ Timeline se anima
□ Console muestra logs
□ Funciona en otra pestaña oculta
□ Se reactiva al volver a pestaña
□ Múltiples órdenes funcionan
□ Sin errores en consola
□ Funciona en Chrome
□ Funciona en Firefox
□ Funciona en Safari
□ Funciona en Edge
□ Funciona en Mobile
```

---

## ✅ Prueba de Aceptación

**Criterio de éxito:**

1. ✅ Cliente ve cambios sin recargar
2. ✅ Cambios aparecen en <5 segundos
3. ✅ Interfaz es responsiva
4. ✅ Sin errores en consola
5. ✅ Funciona en navegadores modernos

**Estado: LISTO PARA PRODUCCIÓN** ✅

---

## 📞 Soporte

Si encuentras problemas:

1. **Verifica logs en consola** (F12)
2. **Verifica estado en Network tab** (F12)
3. **Recarga la página** (F5)
4. **Reinicia el servidor** (stop.sh + start.sh)
5. **Revisa** `docs/AUTO_UPDATE_TRACKING.md`

---

## 🚀 Resumen

**La prueba de actualización automática es simple:**

1. Abre `/track-order` en navegador
2. En otra ventana, cambia estado del pedido
3. Observa cómo se actualiza automáticamente en <5 segundos
4. ¡Listo!

**No se necesita hacer nada especial. Es completamente automático.** ✨
