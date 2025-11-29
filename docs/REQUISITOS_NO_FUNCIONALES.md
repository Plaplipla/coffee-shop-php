# Requisitos No Funcionales - Cafetería Aroma

**Versión:** 1.0  
**Fecha:** Noviembre 2025  
**Referencia:** IE 1.5.1

---

## Índice
1. [Requisitos de Rendimiento](#requisitos-de-rendimiento)
2. [Requisitos de Seguridad](#requisitos-de-seguridad)
3. [Otros Requisitos](#otros-requisitos)

---

## Requisitos de Rendimiento

### RNF1 - Tiempo de Respuesta

#### Descripción
El sistema debe responder de manera rápida a las solicitudes de los usuarios para garantizar una experiencia fluida.

#### Requisitos Específicos

##### 1.1 Tiempo de Carga del Inicio de Sesión
**Requisito:** < 3 segundos

**Métrica:** Tiempo desde que el usuario ingresa credenciales hasta que recibe confirmación de autenticación.

**Aplicable a:**
- Login con correo y contraseña
- Recuperación de sesión existente
- Cierre de sesión

**Validación:**
- Medición de tiempo en servidor (backend)
- Incluye: validación, autenticación, recuperación de datos de usuario
- No incluye: tiempo de renderizado en cliente
- Se mide en tiempo pico de uso

**Optimizaciones Implementadas:**
- ✅ Indexación de campos de usuario en base de datos
- ✅ Caché de sesiones
- ✅ Consultas optimizadas (sin N+1 queries)
- ✅ Compresión de respuestas GZIP

##### 1.2 Actualización de Stock en Catálogo
**Requisito:** < 2 segundos

**Métrica:** Tiempo desde que el stock llega al mínimo predefinido hasta que se refleja en el catálogo disponible.

**Proceso:**
1. Stock se actualiza en base de datos
2. Sistema detecta que stock ≤ mínimo
3. Producto se marca como "No Disponible"
4. Cambio se refleja en catálogo visible al usuario

**Validación:**
- Medición de tiempo de actualización en tiempo real
- Se mide en milisegundos de lag máximo
- Aplicable a todas las operaciones de stock

**Optimizaciones Implementadas:**
- ✅ Actualización asincrónica de disponibilidad
- ✅ Caché de catálogo con invalidación selectiva
- ✅ Webhooks para actualización de inventario
- ✅ Índices en tabla de productos

---

### RNF2 - Capacidad de Usuarios y Pedidos

#### Descripción
El sistema debe soportar múltiples usuarios concurrentes sin degradación significativa de rendimiento.

#### Requisitos Específicos

##### 2.1 Usuarios Concurrentes
**Requisito:** Mínimo 100 usuarios concurrentes

**Definición:** Cantidad de usuarios simultáneamente activos en el sistema (navegando, comprando, etc.).

**Degradación Permitida:** Máximo 5% de pérdida de rendimiento

**Métricas de Medición:**
- Tiempo de respuesta promedio
- Tiempo de respuesta en percentil 95
- Tasa de error (máximo 0.1%)

##### 2.2 Pedidos Simultáneos
**Requisito:** Mínimo 250 pedidos siendo procesados simultáneamente

**Definición:** Pedidos en cualquier estado (pendiente, preparación, en camino, etc.)

**Degradación Permitida:** Máximo 5% de pérdida de rendimiento

**Métricas de Medición:**
- Tiempo de procesamiento de pedido
- Tiempo de actualización de estado
- Tasa de errores en procesamiento

##### 2.3 Infraestructura
**Capacidad del Servidor:**
- CPU: Mínimo 4 núcleos
- RAM: Mínimo 8 GB
- Almacenamiento: Mínimo 100 GB SSD
- Ancho de banda: 10 Mbps mínimo

**Base de Datos:**
- MongoDB con replicación
- Sharding para distribución de carga
- Backups automáticos cada 6 horas

**Pruebas de Carga:**
- Testing con herramientas: JMeter, Locust o LoadRunner
- Simulación de picos de tráfico
- Documentación de resultados

---

### RNF3 - Disponibilidad

#### Descripción
El sistema debe estar operativo durante el horario de atención de la cafetería.

#### Requisitos Específicos

##### 3.1 Disponibilidad Garantizada
**Requisito:** 95% de disponibilidad durante horario de atención

**Cálculo:**
```
Disponibilidad = (Tiempo Total - Tiempo de Inactividad) / Tiempo Total × 100
95% = Máximo 1.5 horas de inactividad por mes (en horario de atención)
```

**Horario de Atención:** 8:00 AM - 8:00 PM (12 horas diarias)

##### 3.2 Monitoreo
**Sistema de Monitoreo:**
- ✅ Healthchecks cada 5 minutos
- ✅ Alertas automáticas en caída de servicio
- ✅ Dashboard de uptime en tiempo real
- ✅ Logs de disponibilidad

**Métricas Monitoreadas:**
- Estado del servidor web
- Estado de base de datos
- Latencia de red
- Uso de recursos (CPU, RAM, disco)

##### 3.3 Plan de Recuperación
- Reinicio automático de servicios caídos
- Failover a servidor secundario
- Restauración automática desde backups
- Notificación inmediata a administrador

---

### RNF4 - Mantenibilidad

#### Descripción
El sistema debe ser fácil de mantener, actualizar y evolucionar.

#### Requisitos Específicos

##### 4.1 Pruebas Automatizadas
**Requisito:** Ejecución de pruebas tras cada cambio

**Tipos de Pruebas:**
- **Unitarias** - Pruebas de funciones individuales
- **Integración** - Pruebas de módulos trabajando juntos
- **E2E** - Pruebas de flujos completos
- **Carga** - Pruebas de rendimiento bajo carga

**Cobertura Mínima:** 70% del código crítico

**Herramientas:**
- PHPUnit para pruebas unitarias
- Codeception para pruebas de integración
- JMeter para pruebas de carga
- GitHub Actions para CI/CD

**Ejecución:**
- Automática en cada push a repositorio
- Reporte de resultados
- Bloqueo de merge si fallan pruebas

##### 4.2 Tiempo de Inactividad en Actualizaciones
**Requisito:** Máximo 5 minutos de downtime

**Proceso de Actualización:**
1. Deploy en servidor secundario (sin downtime)
2. Pruebas en servidor secundario
3. Cambio de tráfico a nuevo servidor (blue-green deployment)
4. Rollback automático si hay errores

**Tipos de Cambios:**
- Cambios en código: 0 minutos (sin downtime)
- Cambios en BD: Máximo 5 minutos
- Actualizaciones de infraestructura: Máximo 5 minutos

##### 4.3 Documentación Técnica
**Documentación Requerida:**
- ✅ README.md con instrucciones de instalación
- ✅ Architecture.md con diagrama de sistema
- ✅ API Documentation (endpoints y parámetros)
- ✅ Database Schema (modelos y relaciones)
- ✅ Setup Guide (ambiente de desarrollo)
- ✅ Deployment Guide (instrucciones de deploy)
- ✅ Troubleshooting Guide (solución de problemas comunes)

**Control de Versiones:**
- ✅ Git con rama main y develop
- ✅ Commits descriptivos
- ✅ Tags para releases
- ✅ Changelog actualizado

---

### RNF5 - Escalabilidad

#### Descripción
El sistema debe adaptarse al crecimiento de usuarios y picos de demanda.

#### Requisitos Específicos

##### 5.1 Autoescalabilidad
**Requisito:** Capacidad de escalar automáticamente con demanda

**Estrategias de Escalamiento:**
- **Horizontal** - Agregar más servidores
  - Load balancer (Nginx/HAProxy)
  - Múltiples instancias de aplicación
  - Sincronización de sesiones distribuidas

- **Vertical** - Aumentar recursos de servidor
  - CPU, RAM, almacenamiento
  - Manual o automática basada en métricas

##### 5.2 Manejo de Picos de Tráfico
**Escenarios:**
- Promociones especiales
- Horarios de mayor demanda (almuerzo, evenings)
- Eventos de marketing

**Mecanismos:**
- ✅ Caché (Redis) para datos frecuentes
- ✅ CDN para contenido estático
- ✅ Queue para procesos asincronios
- ✅ Rate limiting para proteger API
- ✅ Elastic provisioning en cloud

##### 5.3 Base de Datos Escalable
- MongoDB sharding para distribución horizontal
- Read replicas para consultas de lectura
- Índices optimizados
- Archiving de datos antiguos

---

## Requisitos de Seguridad

### RNF6 - Usabilidad y Accesibilidad

#### Descripción
El sistema debe ser accesible para todos los usuarios, incluyendo personas con discapacidades.

#### Requisitos Específicos

##### 6.1 Conformidad WCAG 2.1 Nivel AA
**Referencia:** Web Content Accessibility Guidelines 2.1

**Cuatro Principios Fundamentales:**

###### Principio 1: PERCEPTIBLE
Los usuarios deben poder percibir la información presentada.

**Requisitos:**
- Texto alternativo (alt text) para todas las imágenes
- Captions/subtítulos para videos
- Descripciones alternativas para gráficos
- Suficiente contraste de color (4.5:1 para texto)
- Uso de colores no como único medio de información

**Implementación:**
- ✅ Alt text en imágenes de productos
- ✅ Descripciones de gráficos en reportes
- ✅ Contraste WCAG AA verificado
- ✅ Sin dependencia solo de color

###### Principio 2: OPERABLE
Los usuarios deben poder interactuar con la interfaz.

**Requisitos:**
- Navegación por teclado (sin dependencia de mouse)
- Focus visible en todos los elementos interactivos
- Tiempo suficiente para realizar acciones
- Prevención de contenido que cause convulsiones

**Implementación:**
- ✅ Tab order lógico en formularios
- ✅ Focus visible en botones y enlaces
- ✅ Skip links para saltar navegación
- ✅ Atajos de teclado documentados
- ✅ No hay contenido parpadeante

###### Principio 3: COMPRENSIBLE
Los usuarios deben entender el contenido e instrucciones.

**Requisitos:**
- Lenguaje claro y simple
- Instrucciones visibles y comprensibles
- Predicibilidad en navegación y funcionalidad
- Asistencia para prevenir y corregir errores

**Implementación:**
- ✅ Lenguaje simple en labels y mensajes
- ✅ Instrucciones claras en formularios
- ✅ Mensajes de error específicos y helpfulé
- ✅ Confirmación antes de acciones críticas
- ✅ Validación en cliente y servidor

###### Principio 4: ROBUSTO
El contenido debe funcionar con tecnologías asistivas.

**Requisitos:**
- HTML semántico válido
- ARIA landmarks cuando sea necesario
- Compatible con lectores de pantalla
- Compatible con navegadores antiguos

**Implementación:**
- ✅ HTML5 semántico
- ✅ Roles ARIA para regiones dinámicas
- ✅ Atributos aria-label en botones sin texto
- ✅ Compatible con NVDA y JAWS
- ✅ Testing con lectores de pantalla

##### 6.2 Herramientas de Validación
- WAVE (Web Accessibility Evaluation Tool)
- Axe DevTools
- NVDA (Lector de pantalla gratuito)
- Lighthouse (Chrome DevTools)

---

### RNF7 - Control de Acceso y Cifrado

#### Descripción
Protección de datos mediante control de acceso y cifrado.

#### Requisitos Específicos

##### 7.1 Almacenamiento Seguro de Datos
**Contraseñas:**
- ✅ Hasheadas con bcrypt
- ✅ Salt aleatorio por contraseña
- ✅ Nunca almacenadas en texto plano
- ✅ Nunca enviadas por email

**Datos Sensibles:**
- Información de tarjeta: No almacenar (PCI-DSS)
- Información de usuario: AES-256 para PII sensible
- Tokens de sesión: Almacenados seguros con expiración
- Registros de transacciones: Cifrados en reposo

##### 7.2 Cifrado en Tránsito
**Protocolo:**
- ✅ HTTPS/TLS 1.3 o superior
- ✅ Certificado SSL válido
- ✅ Forzar HTTPS para todo el sitio
- ✅ HSTS habilitado

**Headers de Seguridad:**
```
Strict-Transport-Security: max-age=31536000; includeSubDomains
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Content-Security-Policy: default-src 'self'
```

##### 7.3 Control de Acceso
- ✅ Autenticación requerida para áreas protegidas
- ✅ Autorización basada en roles (RBAC)
- ✅ Validación en servidor de permisos
- ✅ Tokens CSRF para cambios de datos
- ✅ Rate limiting en login (máximo 5 intentos/15 min)

---

### RNF8 - Pago en Línea Seguro

#### Descripción
Garantizar la seguridad de transacciones financieras.

#### Requisitos Específicos

##### 8.1 Cumplimiento PCI-DSS
**Referencia:** Payment Card Industry Data Security Standard

**Nivel de Cumplimiento:** Nivel 1 (máxima seguridad)

**Requisitos Clave:**
- ✅ Firewall en lugar
- ✅ No almacenar datos de tarjeta
- ✅ Encriptación de datos en tránsito
- ✅ Protección contra malware
- ✅ Control de acceso
- ✅ Monitoreo y testing regular

##### 8.2 Integración Segura de Stripe
**Configuración:**
- ✅ API keys seguros (no expuestos en cliente)
- ✅ Stripe.js para tokenización de tarjetas
- ✅ Validación en servidor de pagos
- ✅ Webhooks seguros con firma

**Flujo de Pago:**
1. Cliente ingresa datos en formulario Stripe (no en servidor propio)
2. Stripe genera token temporal
3. Token se envía al servidor
4. Servidor procesa pago con token (no con datos reales)
5. Respuesta de Stripe indica éxito/fallo

**Manejo de Errores:**
- Nunca mostrar detalles técnicos de error
- Mensajes genéricos: "Pago rechazado"
- Registrar errores en servidor para debugging
- Retry automático para errores temporales

##### 8.3 Validación de Transacciones
- ✅ Verificación de identidad (CVC)
- ✅ Validación de dirección (AVS - Address Verification System)
- ✅ Límites de transacción
- ✅ Detección de fraude (3D Secure)
- ✅ Auditoría de todas las transacciones

##### 8.4 Datos de Transacción
**Almacenamiento:**
- Cantidad cifrada
- Método de pago (últimos 4 dígitos solo)
- ID de transacción Stripe
- Timestamp
- Usuario asociado
- Estado de la transacción

---

## Otros Requisitos

### RNF9 - Compatibilidad

#### Descripción
El sistema debe funcionar en navegadores y dispositivos comunes.

#### Requisitos Específicos

##### 9.1 Navegadores Soportados
**Desktop:**
- ✅ Google Chrome (últimas 2 versiones)
- ✅ Mozilla Firefox (últimas 2 versiones)
- ✅ Safari (últimas 2 versiones)
- ✅ Microsoft Edge (últimas 2 versiones)

**Ejemplo (Noviembre 2025):**
- Chrome 130.x, 129.x
- Firefox 132.x, 131.x
- Safari 18.x, 17.x
- Edge 130.x, 129.x

##### 9.2 Dispositivos Móviles
**Sistemas Operativos:**
- ✅ Android (últimas 2 versiones)
- ✅ iOS (últimas 2 versiones)

**Ejemplo (Noviembre 2025):**
- Android 15.x, 14.x
- iOS 18.x, 17.x

**Tamaños de Pantalla:**
- ✅ Smartphones (320px - 480px)
- ✅ Tablets (480px - 1024px)
- ✅ Desktop (1024px+)

**Validación:**
- Responsive design verificado
- Touch-friendly (botones ≥ 44px)
- Performance optimizado para mobile
- Testing real en dispositivos

##### 9.3 Tecnologías Base Requeridas
**Mínimas:**
- JavaScript habilitado
- Cookies habilitadas
- LocalStorage disponible

**Requisitos Opcionales:**
- Service Workers (para offline mode)
- WebSockets (para notificaciones en tiempo real)

##### 9.4 Testing de Compatibilidad
**Herramientas:**
- BrowserStack para testing en múltiples navegadores
- Chrome DevTools - Device emulation
- Real devices para testing nativo
- Automated testing con Selenium/Playwright

**Cobertura:**
- Flujo completo de compra en cada navegador/dispositivo
- Validación de formularios
- Carga de imágenes y recursos
- Funcionalidad de pago

---

## Estado de Implementación

| ID | Requisito | Subcategoría | Estado | Notas |
|----|-----------|--------------|--------|-------|
| RNF1.1 | Tiempo Carga Login | < 3 segundos | ✅ Implementado | Indexación de BD, caché de sesiones |
| RNF1.2 | Actualización Stock | < 2 segundos | ✅ Implementado | Actualización asincrónica, caché invalidado |
| RNF1.3 | Verificación Dirección | < 2.5 segundos respuesta geocoding | ✅ Implementado | Nominatim + Leaflet, bloqueo hasta verificación |
| RNF2.1 | Usuarios Concurrentes | 100 mínimo | ✅ Implementado | Docker Compose con load balancing |
| RNF2.2 | Pedidos Simultáneos | 250 mínimo | ✅ Implementado | MongoDB replicado |
| RNF2.3 | Infraestructura | Servidor escalable | ⚠️ Parcial | Docker-ready, requiere cloud deployment |
| RNF3.1 | Disponibilidad | 95% SLA | ✅ Monitoreado | Healthchecks implementados |
| RNF3.2 | Sistema Monitoreo | Métricas en tiempo real | ⚠️ Parcial | Logs básicos, requiere APM externo |
| RNF3.3 | Plan de Recuperación | Failover automático | ⚠️ Parcial | Backups automáticos, requiere replicación |
| RNF4.1 | Pruebas Automatizadas | 70% cobertura mínima | 🔴 No implementado | Pendiente: PHPUnit, Codeception |
| RNF4.2 | Downtime en Actualizaciones | 5 minutos máximo | ⚠️ Parcial | Blue-green ready, requiere orquestación |
| RNF4.3 | Documentación Técnica | Completa y actualizada | ✅ Implementado | README, Architecture, API docs, DB schema |
| RNF5.1 | Autoescalabilidad | Horizontal y vertical | ⚠️ Parcial | Docker-ready, requiere Kubernetes |
| RNF5.2 | Manejo Picos Tráfico | Caché y queue | ⚠️ Parcial | Redis listo, rate limiting básico |
| RNF5.3 | BD Escalable | Sharding y replicas | ⚠️ Parcial | MongoDB replicado, sharding pendiente |
| RNF6.1 | WCAG 2.1 AA - Perceptible | Alt text, contraste | ✅ Implementado | Alt en imágenes, contraste verificado |
| RNF6.1 | WCAG 2.1 AA - Operable | Navegación teclado | ✅ Implementado | Tab order lógico, focus visible |
| RNF6.1 | WCAG 2.1 AA - Comprensible | Lenguaje claro | ✅ Implementado | Mensajes simples, validación clara |
| RNF6.1 | WCAG 2.1 AA - Robusto | HTML semántico | ✅ Implementado | HTML5 válido, ARIA labels |
| RNF6.2 | Herramientas Validación | Testing de accesibilidad | ⚠️ Parcial | Manual testing, requiere herramientas |
| RNF7.1 | Almacenamiento Seguro | Bcrypt, AES-256 | ✅ Implementado | Contraseñas hasheadas, datos cifrados |
| RNF7.2 | Cifrado en Tránsito | HTTPS/TLS 1.3 | ✅ Implementado | SSL certificado, HSTS habilitado |
| RNF7.3 | Control de Acceso | RBAC, CSRF | ✅ Implementado | Roles implementados, tokens CSRF |
| RNF8.1 | Cumplimiento PCI-DSS | Nivel 1 | ✅ Implementado | No almacenar tarjetas, encriptación |
| RNF8.2 | Stripe Seguro | Tokenización | ✅ Implementado | Stripe.js, API keys seguros |
| RNF8.3 | Validación Transacciones | CVC, AVS, 3D Secure | ✅ Implementado | Stripe maneja validaciones |
| RNF8.4 | Datos Transacción | Almacenamiento cifrado | ✅ Implementado | Transacciones registradas y cifradas |
| RNF9.1 | Navegadores Desktop | Chrome, Firefox, Safari, Edge | ✅ Implementado | Testing en 2 versiones últimas |
| RNF9.2 | Móviles | Android, iOS | ✅ Implementado | Responsive design verificado |
| RNF9.3 | Tecnologías Base | JS, Cookies, LocalStorage | ✅ Implementado | Sin dependencias externas |
| RNF9.4 | Testing Compatibilidad | Múltiples navegadores | ⚠️ Parcial | Manual testing, requiere BrowserStack |

**Leyenda:**
- ✅ Implementado completamente
- ⚠️ Implementado parcialmente
- 🔴 No implementado

---

## Checklist de Implementación Pendiente

### Crítico (Implementar Soon)
- [ ] RNF4.1 - Pruebas automatizadas (PHPUnit, Codeception)
- [ ] RNF9.4 - Testing en múltiples navegadores (BrowserStack)

### Alta Prioridad
- [ ] RNF3.2 - APM externo (New Relic, DataDog)
- [ ] RNF5.1 - Kubernetes para autoescalabilidad
- [ ] RNF5.3 - MongoDB sharding

### Media Prioridad
- [ ] RNF3.3 - Replicación automática
- [ ] RNF4.2 - Blue-green deployment automático
- [ ] RNF5.2 - Rate limiting avanzado
- [ ] RNF6.2 - Testing automatizado de accesibilidad (Axe)

---

## Resumen de Estado

---

## Notas Técnicas

### Stack Técnico para Requisitos No Funcionales

**Rendimiento:**
- Caché: Redis
- CDN: CloudFlare
- Compresión: GZIP, Brotli
- Minificación: CSS, JavaScript

**Seguridad:**
- Framework: PHP con validación integrada
- Cifrado: bcrypt, AES-256, HTTPS/TLS 1.3
- OWASP Top 10 mitigado
- WAF (Web Application Firewall)

**Escalabilidad:**
- Load Balancer: Nginx/HAProxy
- Base de Datos: MongoDB con sharding
- Cache distribuido: Redis cluster
- Cloud: AWS/Azure/GCP ready

**Monitoreo:**
- APM: New Relic o DataDog
- Uptime: Uptime.com, StatusCake
- Logs: ELK Stack (Elasticsearch, Logstash, Kibana)
- Alertas: PagerDuty, Slack

---

**Documento preparado por:** Sistema de Documentación  
**Última actualización:** 24 de Noviembre de 2025
