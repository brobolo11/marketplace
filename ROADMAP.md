# 🏗️ ROADMAP - HouseFixes Marketplace
## Plan de Desarrollo Completo

---

## 📋 FASE 1: SISTEMA DE AUTENTICACIÓN Y NAVEGACIÓN

### 1.1 Rediseño del Flujo de Login
- [ ] **Modificar RedirectIfAuthenticated middleware**
  - Archivo: `app/Http/Middleware/RedirectIfAuthenticated.php`
  - Cambiar redirección de `/dashboard` a `/` (landing)
  
- [ ] **Actualizar Fortify configuration**
  - Archivo: `config/fortify.php`
  - Cambiar `home` de `/dashboard` a `/`
  
- [ ] **Eliminar/Actualizar rutas de dashboard**
  - Archivo: `routes/web.php`
  - Mantener landing como página principal autenticada

### 1.2 Menú Desplegable de Perfil (Dropdown)
- [ ] **Crear componente de dropdown por rol**
  - Archivo: `resources/views/components/user-dropdown.blade.php`
  - Ubicación: Esquina superior derecha del navbar
  
- [ ] **Opciones para CLIENTE:**
  - Gestionar Perfil
  - Mis Reservas
  - Mensajes (con badge de notificaciones)
  - Cerrar Sesión
  
- [ ] **Opciones para PROFESIONAL:**
  - Gestionar Perfil
  - Mis Servicios
  - Solicitudes Pendientes (con badge de conteo)
  - Mis Reservas Activas
  - Disponibilidad/Calendario
  - Mensajes (con badge de notificaciones)
  - Cerrar Sesión
  
- [ ] **Opciones para ADMIN:**
  - Panel de Administración
  - Gestionar Usuarios
  - Gestionar Servicios
  - Gestionar Reservas
  - Gestionar Pagos
  - Cerrar Sesión

---

## 📋 FASE 2: FLUJO DE TRABAJO DEL CLIENTE

### 2.1 Sistema de Contratación de Servicios
- [ ] **Botón "Contratar" en tarjeta de servicio**
  - Archivo: `resources/views/services/index.blade.php`
  - Redirige a modal de selección de fecha
  
- [ ] **Modal de Selección de Calendario**
  - Archivo: `resources/views/components/booking-calendar-modal.blade.php`
  - Integrar librería de calendario (ej: Fullcalendar.js)
  - Mostrar días disponibles del profesional (verde)
  - Mostrar días no disponibles (gris)
  - Permitir selección de rango de fechas
  
- [ ] **Formulario de Solicitud de Reserva**
  - Campos: fecha_inicio, fecha_fin, descripción, notas
  - Validación de disponibilidad en tiempo real
  
- [ ] **Crear Booking con estado "pendiente"**
  - Archivo: `app/Http/Controllers/BookingController.php`
  - Método: `store()`
  - Estado inicial: `pending`
  - Enviar notificación al profesional

### 2.2 Sistema de Notificaciones en Dropdown
- [ ] **Agregar tabla de notificaciones**
  - Migración: `create_notifications_table.php`
  - Campos: user_id, type, title, message, link, read_at, created_at
  
- [ ] **Badge de contador en dropdown**
  - Mostrar número de notificaciones no leídas
  - Actualización en tiempo real (Livewire o polling AJAX)
  
- [ ] **Panel de Mensajes/Notificaciones**
  - Archivo: `resources/views/messages/index.blade.php`
  - Lista de notificaciones ordenadas por fecha
  - Marcar como leída al hacer clic
  - Tipos: booking_accepted, booking_rejected, payment_required, message_received

### 2.3 Sistema de Pago Simulado (Post-Aprobación)
- [ ] **Notificación de pago requerido**
  - Cuando profesional acepta, crear notificación "payment_required"
  - Mostrar en dropdown de mensajes
  
- [ ] **Página de Pago**
  - Archivo: `resources/views/payments/create.blade.php`
  - Mostrar detalles de la reserva
  - Formulario simulado de tarjeta de crédito
  - Botón "Pagar" (simula procesamiento)
  
- [ ] **Procesar Pago Simulado**
  - Archivo: `app/Http/Controllers/PaymentController.php`
  - Crear registro en tabla `payments`
  - Actualizar booking a estado "paid"
  - Enviar confirmación al cliente y profesional

### 2.4 Vista de "Mis Reservas" (Cliente)
- [ ] **Crear página de reservas del cliente**
  - Archivo: `resources/views/bookings/my-bookings.blade.php`
  - Mostrar todas las reservas con estados
  - Filtros: pendientes, aceptadas, pagadas, completadas, canceladas
  - Opción de cancelar reservas pendientes
  - Opción de dejar reseña en reservas completadas

---

## 📋 FASE 3: FLUJO DE TRABAJO DEL PROFESIONAL

### 3.1 Gestión de Servicios (Vista Especial)
- [ ] **Modificar vista de servicios para profesionales**
  - Archivo: `resources/views/services/index.blade.php`
  - Detectar si user es profesional: `auth()->user()->role === 'professional'`
  - Resaltar servicios propios con borde especial (ej: border-blue-500)
  - Mostrar iconos de editar/eliminar en servicios propios
  
- [ ] **Botón "Añadir Servicio" (arriba de la página)**
  - Solo visible para profesionales
  - Abre modal de creación

### 3.2 Modal de Gestión de Servicios
- [ ] **Modal de Crear/Editar Servicio**
  - Archivo: `resources/views/components/service-modal.blade.php`
  - Campos: title, description, category_id, price, duration
  - Upload de fotos (hasta 5 imágenes)
  - Validación en frontend y backend
  
- [ ] **Controlador de Servicios (CRUD completo)**
  - Archivo: `app/Http/Controllers/ServiceController.php`
  - `store()`: Crear servicio
  - `update()`: Editar servicio
  - `destroy()`: Eliminar servicio (soft delete recomendado)
  - Validar que el profesional solo edite sus propios servicios

### 3.3 Sistema de Disponibilidad/Calendario
- [ ] **Crear tabla de disponibilidad**
  - Migración: `add_availability_fields_to_users.php` o tabla separada
  - Campos: user_id, day_of_week (1-7), start_time, end_time, is_available
  - Permitir múltiples franjas horarias por día
  
- [ ] **Página de Gestión de Disponibilidad**
  - Archivo: `resources/views/availability/manage.blade.php`
  - Calendario visual para seleccionar días disponibles
  - Configurar horarios por día de la semana
  - Marcar días específicos como no disponibles (vacaciones)
  
- [ ] **Controlador de Disponibilidad**
  - Archivo: `app/Http/Controllers/AvailabilityController.php`
  - `index()`: Mostrar disponibilidad actual
  - `update()`: Actualizar configuración
  - API endpoint para consultar disponibilidad (usado por clientes)

### 3.4 Sistema de Solicitudes Pendientes
- [ ] **Página de Solicitudes Pendientes**
  - Archivo: `resources/views/bookings/pending-requests.blade.php`
  - Mostrar todas las reservas con estado "pending"
  - Tarjetas con info del cliente, servicio, fechas, descripción
  - Botones: "Aceptar" y "Rechazar"
  - Badge en dropdown con número de solicitudes
  
- [ ] **Lógica de Aceptar/Rechazar**
  - Archivo: `app/Http/Controllers/BookingController.php`
  - `approve()`: Cambiar estado a "approved", enviar notificación de pago
  - `reject()`: Cambiar estado a "rejected", enviar notificación al cliente
  - Validar disponibilidad antes de aceptar
  - Bloquear calendario si se acepta

### 3.5 Vista de "Mis Reservas Activas" (Profesional)
- [ ] **Crear página de reservas activas**
  - Archivo: `resources/views/bookings/professional-bookings.blade.php`
  - Mostrar reservas aceptadas y pagadas
  - Opción de marcar como completada
  - Opción de cancelar (con penalización o reembolso)
  - Filtros por estado y fecha

---

## 📋 FASE 4: PANEL DE ADMINISTRACIÓN

### 4.1 Dashboard de Admin
- [ ] **Crear landing especial para admin**
  - Archivo: `resources/views/admin/dashboard.blade.php`
  - Estadísticas generales:
    - Total de usuarios (clientes, profesionales)
    - Total de servicios activos
    - Total de reservas (por estado)
    - Total de ingresos (pagos completados)
  - Gráficos de tendencias (opcional)

### 4.2 Gestión de Servicios (Admin)
- [ ] **Vista de todos los servicios**
  - Archivo: `resources/views/admin/services.blade.php`
  - Tabla con todos los servicios
  - Filtros: categoría, profesional, estado
  - Acciones: ver, editar, eliminar, suspender
  
- [ ] **Controlador Admin de Servicios**
  - Archivo: `app/Http/Controllers/Admin/ServiceController.php`
  - CRUD completo sin restricciones de usuario
  - Poder suspender servicios inapropiados

### 4.3 Gestión de Reservas (Admin)
- [ ] **Vista de todas las reservas**
  - Archivo: `resources/views/admin/bookings.blade.php`
  - Tabla con filtros avanzados
  - Ver timeline completo de cada reserva
  - Poder cancelar o modificar cualquier reserva
  - Ver mensajes entre cliente y profesional

### 4.4 Gestión de Pagos (Admin)
- [ ] **Vista de todos los pagos**
  - Archivo: `resources/views/admin/payments.blade.php`
  - Lista de todos los pagos procesados
  - Filtros por fecha, usuario, monto
  - Reportes exportables (CSV/PDF)
  - Indicadores de pagos fallidos o reembolsos

### 4.5 Gestión de Usuarios (Admin)
- [ ] **Vista de todos los usuarios**
  - Archivo: `resources/views/admin/users.blade.php`
  - Tabla con filtros por rol
  - Acciones: ver perfil, editar, suspender, eliminar
  - Cambiar roles de usuarios
  - Ver histórico de actividad

---

## 📋 FASE 5: SISTEMA DE MENSAJERÍA MEJORADO

### 5.1 Chat entre Cliente y Profesional
- [ ] **Modificar tabla messages**
  - Migración: `update_messages_table.php`
  - Campos: booking_id (relacionar con reserva), read_at, attachment
  
- [ ] **Vista de Chat Individual**
  - Archivo: `resources/views/messages/chat.blade.php`
  - Interface de chat estilo WhatsApp
  - Mostrar mensajes por booking
  - Enviar y recibir mensajes en tiempo real (Livewire o Pusher)
  
- [ ] **Lista de Conversaciones**
  - Archivo: `resources/views/messages/conversations.blade.php`
  - Listar todas las conversaciones activas
  - Badge con mensajes no leídos
  - Filtrar por reserva activa/completada

### 5.2 Notificaciones en Tiempo Real
- [ ] **Implementar sistema de notificaciones**
  - Opción 1: Livewire polling (simple, sin servidor adicional)
  - Opción 2: Laravel Broadcasting + Pusher (tiempo real completo)
  - Actualizar badge de mensajes automáticamente

---

## 📋 FASE 6: INTEGRACIONES Y API

### 6.1 Integración de Calendario API
- [ ] **Seleccionar librería de calendario**
  - Recomendado: Fullcalendar.js (MIT license)
  - Alternativas: Flatpickr, VanillaCalendar
  
- [ ] **Instalar y configurar**
  - `npm install @fullcalendar/core @fullcalendar/daygrid @fullcalendar/interaction`
  - Archivo: `resources/js/calendar.js`
  
- [ ] **API endpoints para calendario**
  - Archivo: `routes/api.php`
  - `GET /api/availability/{professional_id}`: Obtener días disponibles
  - `GET /api/bookings/{professional_id}`: Obtener días ocupados
  
- [ ] **Integrar en modal de reserva**
  - Mostrar calendario con disponibilidad en tiempo real
  - Bloquear fechas ya reservadas
  - Validación de selección antes de enviar

### 6.2 API REST para Mobile (Futuro)
- [ ] **Crear API RESTful completa**
  - Archivo: `routes/api.php`
  - Autenticación con Sanctum
  - Endpoints para servicios, bookings, pagos, mensajes
  - Documentación con Swagger/OpenAPI

---

## 📋 FASE 7: BASE DE DATOS Y MIGRACIONES

### 7.1 Nuevas Tablas Requeridas
- [ ] **Tabla: notifications**
  ```sql
  id, user_id, type, title, message, link, read_at, created_at
  ```

- [ ] **Tabla: availability (o campos en users)**
  ```sql
  id, user_id, day_of_week, start_time, end_time, is_available, date (opcional)
  ```

- [ ] **Actualizar tabla: bookings**
  ```sql
  Añadir campos: approved_at, rejected_at, rejection_reason, completed_at
  ```

- [ ] **Actualizar tabla: messages**
  ```sql
  Añadir campos: booking_id, read_at, attachment_path
  ```

- [ ] **Actualizar tabla: payments**
  ```sql
  Añadir campos: payment_method, transaction_id, refunded_at, refund_amount
  ```

### 7.2 Índices y Optimización
- [ ] **Crear índices para consultas frecuentes**
  - `bookings`: índice en `user_id`, `professional_id`, `status`
  - `notifications`: índice en `user_id`, `read_at`
  - `messages`: índice en `booking_id`, `read_at`
  - `availability`: índice en `user_id`, `day_of_week`

---

## 📋 FASE 8: UI/UX Y COMPONENTES

### 8.1 Componentes Reutilizables
- [ ] **Componente: Badge de Notificación**
  - Archivo: `resources/views/components/notification-badge.blade.php`
  - Mostrar contador en rojo
  
- [ ] **Componente: Tarjeta de Servicio (Modo Edición)**
  - Archivo: `resources/views/components/service-card-editable.blade.php`
  - Iconos de editar/eliminar overlay
  
- [ ] **Componente: Modal Base**
  - Archivo: `resources/views/components/modal.blade.php`
  - Reutilizable para servicios, calendario, confirmaciones
  
- [ ] **Componente: Estado de Reserva**
  - Archivo: `resources/views/components/booking-status-badge.blade.php`
  - Colores por estado: pending (amarillo), approved (verde), rejected (rojo), completed (azul)

### 8.2 Mejoras de Accesibilidad
- [ ] **Agregar atributos ARIA**
  - Modals, dropdowns, notificaciones
  
- [ ] **Navegación por teclado**
  - Tab navigation funcional
  - Escape para cerrar modales
  
- [ ] **Responsive completo**
  - Menú móvil para dropdown de perfil
  - Modales adaptables a pantallas pequeñas

---

## 📋 FASE 9: SEGURIDAD Y VALIDACIÓN

### 9.1 Políticas de Autorización
- [ ] **Crear Policy para Service**
  - Archivo: `app/Policies/ServicePolicy.php`
  - Métodos: `update()`, `delete()` - solo propietario o admin
  
- [ ] **Crear Policy para Booking**
  - Archivo: `app/Policies/BookingPolicy.php`
  - Métodos: `approve()` - solo profesional asignado
  - `cancel()` - cliente o profesional según estado
  
- [ ] **Middleware de Rol**
  - Archivo: `app/Http/Middleware/CheckRole.php`
  - Proteger rutas de admin: `middleware(['auth', 'role:admin'])`
  - Proteger rutas de profesional: `middleware(['auth', 'role:professional'])`

### 9.2 Validaciones de Negocio
- [ ] **Validar disponibilidad antes de reservar**
  - No permitir reservas en fechas ocupadas
  - Validar horarios laborales del profesional
  
- [ ] **Validar pagos antes de confirmar**
  - Estado de booking debe ser "approved"
  - Monto correcto del servicio
  
- [ ] **Prevenir double-booking**
  - Lock de transacción al aprobar booking
  - Verificar disponibilidad en tiempo real

---

## 📋 FASE 10: TESTING Y DEPLOYMENT

### 10.1 Testing
- [ ] **Tests Unitarios**
  - Models: User, Service, Booking, Payment
  - Validaciones de negocio
  
- [ ] **Tests de Integración**
  - Flujo completo: registro → búsqueda → reserva → pago
  - Flujo de profesional: crear servicio → recibir solicitud → aprobar
  - Flujo de admin: gestionar usuarios y servicios
  
- [ ] **Tests de API**
  - Endpoints de disponibilidad
  - Endpoints de notificaciones

### 10.2 Optimización de Performance
- [ ] **Eager Loading**
  - Cargar relaciones en consultas (with())
  - Evitar problema N+1
  
- [ ] **Caching**
  - Cache de servicios activos (5 minutos)
  - Cache de disponibilidad (1 minuto)
  
- [ ] **Paginación**
  - Todos los listados con paginación
  - Lazy loading de imágenes

### 10.3 Deployment
- [ ] **Configurar entorno de producción**
  - Variables de entorno (.env.production)
  - Optimizar assets: `npm run build`
  - Cache de rutas y config: `php artisan optimize`
  
- [ ] **Base de datos**
  - Migraciones en producción
  - Seeders para datos iniciales (categorías)
  
- [ ] **Backup y Recuperación**
  - Script de backup automático
  - Plan de recuperación ante desastres

---

## 📊 RESUMEN DE PRIORIDADES

### 🔴 CRÍTICO (Empezar YA)
1. Redirección de login al landing (Fase 1.1)
2. Dropdown de perfil con opciones por rol (Fase 1.2)
3. Modal de calendario para reservas (Fase 2.1)
4. Sistema de solicitudes pendientes (Fase 3.4)
5. Modal de gestión de servicios (Fase 3.2)

### 🟡 IMPORTANTE (Siguientes 2 semanas)
6. Sistema de notificaciones (Fase 2.2)
7. Gestión de disponibilidad (Fase 3.3)
8. Panel de admin básico (Fase 4.1-4.2)
9. Proceso de pago simulado (Fase 2.3)
10. Políticas de autorización (Fase 9.1)

### 🟢 DESEABLE (Backlog)
11. Chat en tiempo real (Fase 5.1-5.2)
12. API REST completa (Fase 6.2)
13. Tests completos (Fase 10.1)
14. Optimizaciones de performance (Fase 10.2)

---

## 📝 NOTAS TÉCNICAS

### Stack Tecnológico
- **Backend**: Laravel 12.x
- **Frontend**: Blade + Tailwind CSS + Alpine.js
- **Calendario**: Fullcalendar.js
- **Autenticación**: Jetstream + Fortify
- **Base de datos**: MySQL
- **Assets**: Vite
- **Notificaciones**: Livewire (polling) o Laravel Broadcasting (tiempo real)

### Estimación de Tiempo
- **Fase 1-2**: 1-2 semanas (autenticación y flujo cliente)
- **Fase 3**: 2 semanas (flujo profesional)
- **Fase 4**: 1 semana (panel admin)
- **Fase 5-6**: 1-2 semanas (mensajería y API)
- **Fase 7-10**: 1-2 semanas (BD, seguridad, testing)

**TOTAL ESTIMADO**: 6-10 semanas de desarrollo

---

## ✅ CHECKLIST DE INICIO RÁPIDO

### Primera Sesión de Desarrollo (HOY)
- [ ] Modificar `RedirectIfAuthenticated.php`
- [ ] Actualizar `config/fortify.php`
- [ ] Crear componente `user-dropdown.blade.php`
- [ ] Integrar dropdown en `marketplace.blade.php`
- [ ] Crear migración de `notifications`
- [ ] Instalar Fullcalendar.js: `npm install @fullcalendar/core @fullcalendar/daygrid @fullcalendar/interaction`

### Segunda Sesión
- [ ] Crear modal de calendario de reservas
- [ ] Crear página de solicitudes pendientes (profesional)
- [ ] Implementar lógica de aprobar/rechazar booking
- [ ] Crear modal de gestión de servicios

### Tercera Sesión
- [ ] Sistema de notificaciones básico
- [ ] Página de disponibilidad para profesionales
- [ ] Proceso de pago simulado completo
- [ ] Panel de admin básico

---

**Última actualización**: 18 de Noviembre de 2025
**Estado del proyecto**: 40% completado (estructura base, autenticación, servicios, reservas básicas)
**Próximo milestone**: Sistema de autenticación rediseñado + Dropdown de perfil
