# 📊 TABLA DE SEGUIMIENTO DE TAREAS - HouseFixes

## 🎯 SPRINT 1: AUTENTICACIÓN Y NAVEGACIÓN (Semana 1)

| # | Tarea | Archivo | Prioridad | Estado | Tiempo Est. |
|---|-------|---------|-----------|--------|-------------|
| 1 | Modificar redirección después del login | `app/Http/Middleware/RedirectIfAuthenticated.php` | 🔴 CRÍTICO | ⏳ Pendiente | 15 min |
| 2 | Actualizar configuración de Fortify home | `config/fortify.php` | 🔴 CRÍTICO | ⏳ Pendiente | 5 min |
| 3 | Crear componente dropdown de usuario | `resources/views/components/user-dropdown.blade.php` | 🔴 CRÍTICO | ⏳ Pendiente | 1 hora |
| 4 | Integrar dropdown en navbar | `resources/views/layouts/marketplace.blade.php` | 🔴 CRÍTICO | ⏳ Pendiente | 30 min |
| 5 | Crear migración de notificaciones | `database/migrations/create_notifications_table.php` | 🔴 CRÍTICO | ⏳ Pendiente | 30 min |
| 6 | Crear modelo Notification | `app/Models/Notification.php` | 🔴 CRÍTICO | ⏳ Pendiente | 15 min |
| 7 | Instalar Fullcalendar.js | Terminal: `npm install @fullcalendar/*` | 🔴 CRÍTICO | ⏳ Pendiente | 10 min |

**TOTAL SPRINT 1**: ~3 horas

---

## 🎯 SPRINT 2: SISTEMA DE RESERVAS CON CALENDARIO (Semana 1-2)

| # | Tarea | Archivo | Prioridad | Estado | Tiempo Est. |
|---|-------|---------|-----------|--------|-------------|
| 8 | Crear componente modal de calendario | `resources/views/components/booking-calendar-modal.blade.php` | 🔴 CRÍTICO | ⏳ Pendiente | 2 horas |
| 9 | Crear archivo JS para calendario | `resources/js/calendar.js` | 🔴 CRÍTICO | ⏳ Pendiente | 1.5 horas |
| 10 | Actualizar migración de bookings | `database/migrations/*_bookings_table.php` | 🔴 CRÍTICO | ⏳ Pendiente | 30 min |
| 11 | Crear API de disponibilidad | `routes/api.php` + Controller | 🔴 CRÍTICO | ⏳ Pendiente | 1 hora |
| 12 | Modificar botón "Contratar" en servicios | `resources/views/services/index.blade.php` | 🔴 CRÍTICO | ⏳ Pendiente | 20 min |
| 13 | Implementar lógica de crear booking | `app/Http/Controllers/BookingController.php` | 🔴 CRÍTICO | ⏳ Pendiente | 1 hora |

**TOTAL SPRINT 2**: ~6.5 horas

---

## 🎯 SPRINT 3: GESTIÓN DE SERVICIOS (PROFESIONAL) (Semana 2)

| # | Tarea | Archivo | Prioridad | Estado | Tiempo Est. |
|---|-------|---------|-----------|--------|-------------|
| 14 | Crear modal de gestión de servicios | `resources/views/components/service-modal.blade.php` | 🔴 CRÍTICO | ⏳ Pendiente | 2 horas |
| 15 | Modificar vista de servicios para pros | `resources/views/services/index.blade.php` | 🔴 CRÍTICO | ⏳ Pendiente | 1 hora |
| 16 | Implementar CRUD de servicios | `app/Http/Controllers/ServiceController.php` | 🔴 CRÍTICO | ⏳ Pendiente | 2 horas |
| 17 | Crear Policy para Service | `app/Policies/ServicePolicy.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 30 min |
| 18 | Agregar upload de imágenes | Controller + Vista | 🟡 IMPORTANTE | ⏳ Pendiente | 1.5 horas |

**TOTAL SPRINT 3**: ~7 horas

---

## 🎯 SPRINT 4: SOLICITUDES PENDIENTES (PROFESIONAL) (Semana 2-3) ✅ COMPLETADO

| # | Tarea | Archivo | Prioridad | Estado | Tiempo Est. |
|---|-------|---------|-----------|--------|-------------|
| 19 | Crear vista de solicitudes pendientes | `resources/views/bookings/pending-requests.blade.php` | 🔴 CRÍTICO | ✅ Completado | 1.5 horas |
| 20 | Crear rutas para solicitudes/approve/reject | `routes/web.php` | 🔴 CRÍTICO | ✅ Completado | 10 min |
| 21 | Implementar approve() en controller | `app/Http/Controllers/BookingController.php` | 🔴 CRÍTICO | ✅ Completado | 1 hora |
| 22 | Implementar reject() en controller | `app/Http/Controllers/BookingController.php` | 🔴 CRÍTICO | ✅ Completado | 45 min |
| 23 | Sistema de notificaciones (aprobar/rechazar) | `app/Services/NotificationService.php` | 🟡 IMPORTANTE | ✅ Completado | 1 hora |
| 24 | Badge de contador en dropdown | `resources/views/components/user-dropdown.blade.php` | 🟡 IMPORTANTE | ✅ Completado | 30 min |

**TOTAL SPRINT 4**: ~5 horas ✅ COMPLETADO

---

## 🎯 SPRINT 5: SISTEMA DE PAGOS SIMULADO (Semana 3) ✅ COMPLETADO

| # | Tarea | Archivo | Prioridad | Estado | Tiempo Est. |
|---|-------|---------|-----------|--------|-------------|
| 25 | Crear vista de pago (checkout) | `resources/views/payments/checkout.blade.php` | 🟡 IMPORTANTE | ✅ Completado | 1.5 horas |
| 26 | Implementar PaymentController | `app/Http/Controllers/PaymentController.php` | 🟡 IMPORTANTE | ✅ Completado | 1 hora |
| 27 | Notificación de pago requerido | NotificationService | 🟡 IMPORTANTE | ✅ Completado | 30 min |
| 28 | Actualizar estado de booking tras pago | Controller + Migration | 🟡 IMPORTANTE | ✅ Completado | 30 min |
| 29 | Vista de confirmación de pago | `resources/views/payments/confirmation.blade.php` | 🟡 IMPORTANTE | ✅ Completado | 45 min |

**TOTAL SPRINT 5**: ~4.5 horas ✅ COMPLETADO

---

## 🎯 SPRINT 6: GESTIÓN DE DISPONIBILIDAD (Semana 3-4)

| # | Tarea | Archivo | Prioridad | Estado | Tiempo Est. |
|---|-------|---------|-----------|--------|-------------|
| 30 | Crear migración de availability | `database/migrations/*_availability_table.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 30 min |
| 31 | Crear modelo Availability | `app/Models/Availability.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 20 min |
| 32 | Vista de gestión de disponibilidad | `resources/views/availability/manage.blade.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 2 horas |
| 33 | AvailabilityController (CRUD) | `app/Http/Controllers/AvailabilityController.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 1.5 horas |
| 34 | Integrar disponibilidad en calendario | `resources/js/calendar.js` | 🟡 IMPORTANTE | ⏳ Pendiente | 1 hora |

**TOTAL SPRINT 6**: ~5 horas

---

## 🎯 SPRINT 7: PANEL DE ADMINISTRACIÓN (Semana 4)

| # | Tarea | Archivo | Prioridad | Estado | Tiempo Est. |
|---|-------|---------|-----------|--------|-------------|
| 35 | Crear middleware CheckRole | `app/Http/Middleware/CheckRole.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 30 min |
| 36 | Dashboard de admin | `resources/views/admin/dashboard.blade.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 2 horas |
| 37 | Vista de gestión de servicios (admin) | `resources/views/admin/services.blade.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 1.5 horas |
| 38 | Vista de gestión de reservas (admin) | `resources/views/admin/bookings.blade.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 1.5 horas |
| 39 | Vista de gestión de pagos (admin) | `resources/views/admin/payments.blade.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 1 hora |
| 40 | Vista de gestión de usuarios (admin) | `resources/views/admin/users.blade.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 1.5 horas |
| 41 | AdminController base | `app/Http/Controllers/Admin/AdminController.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 1 hora |

**TOTAL SPRINT 7**: ~9 horas

---

## 🎯 SPRINT 8: VISTAS DE USUARIO (Semana 4-5)

| # | Tarea | Archivo | Prioridad | Estado | Tiempo Est. |
|---|-------|---------|-----------|--------|-------------|
| 42 | Vista "Mis Reservas" (cliente) | `resources/views/bookings/my-bookings.blade.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 1.5 horas |
| 43 | Vista "Mis Reservas Activas" (pro) | `resources/views/bookings/professional-bookings.blade.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 1.5 horas |
| 44 | Panel de mensajes/notificaciones | `resources/views/messages/index.blade.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 2 horas |
| 45 | Implementar marcar como leído | Controller | 🟡 IMPORTANTE | ⏳ Pendiente | 30 min |

**TOTAL SPRINT 8**: ~5.5 horas

---

## 🎯 SPRINT 9: COMPONENTES REUTILIZABLES (Semana 5)

| # | Tarea | Archivo | Prioridad | Estado | Tiempo Est. |
|---|-------|---------|-----------|--------|-------------|
| 46 | Componente badge de notificación | `resources/views/components/notification-badge.blade.php` | 🟢 DESEABLE | ⏳ Pendiente | 30 min |
| 47 | Componente tarjeta editable | `resources/views/components/service-card-editable.blade.php` | 🟢 DESEABLE | ⏳ Pendiente | 45 min |
| 48 | Componente modal base | `resources/views/components/modal.blade.php` | 🟢 DESEABLE | ⏳ Pendiente | 1 hora |
| 49 | Componente estado de reserva | `resources/views/components/booking-status-badge.blade.php` | 🟢 DESEABLE | ⏳ Pendiente | 30 min |
| 50 | Refactorizar modales existentes | Múltiples archivos | 🟢 DESEABLE | ⏳ Pendiente | 1.5 horas |

**TOTAL SPRINT 9**: ~4.5 horas

---

## 🎯 SPRINT 10: SEGURIDAD Y POLICIES (Semana 5-6)

| # | Tarea | Archivo | Prioridad | Estado | Tiempo Est. |
|---|-------|---------|-----------|--------|-------------|
| 51 | BookingPolicy completa | `app/Policies/BookingPolicy.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 1 hora |
| 52 | Validación de disponibilidad | Service class | 🔴 CRÍTICO | ⏳ Pendiente | 1.5 horas |
| 53 | Prevenir double-booking | Controller con locks | 🔴 CRÍTICO | ⏳ Pendiente | 1 hora |
| 54 | Validaciones de pago | PaymentController | 🟡 IMPORTANTE | ⏳ Pendiente | 45 min |
| 55 | Proteger rutas con middleware | `routes/web.php` | 🟡 IMPORTANTE | ⏳ Pendiente | 30 min |

**TOTAL SPRINT 10**: ~4.5 horas

---

## 📈 RESUMEN GENERAL

| Sprint | Enfoque | Tareas | Tiempo Total | Prioridad |
|--------|---------|--------|--------------|-----------|
| **Sprint 1** | Autenticación y Navegación | 7 | ~3 horas | 🔴 CRÍTICO |
| **Sprint 2** | Sistema de Reservas | 6 | ~6.5 horas | 🔴 CRÍTICO |
| **Sprint 3** | Gestión de Servicios | 5 | ~7 horas | 🔴 CRÍTICO |
| **Sprint 4** | Solicitudes Pendientes | 6 | ~5 horas | 🔴 CRÍTICO |
| **Sprint 5** | Sistema de Pagos | 5 | ~4.5 horas | 🟡 IMPORTANTE |
| **Sprint 6** | Gestión de Disponibilidad | 5 | ~5 horas | 🟡 IMPORTANTE |
| **Sprint 7** | Panel de Administración | 7 | ~9 horas | 🟡 IMPORTANTE |
| **Sprint 8** | Vistas de Usuario | 4 | ~5.5 horas | 🟡 IMPORTANTE |
| **Sprint 9** | Componentes Reutilizables | 5 | ~4.5 horas | 🟢 DESEABLE |
| **Sprint 10** | Seguridad y Policies | 5 | ~4.5 horas | 🟡 IMPORTANTE |
| **TOTAL** | **10 Sprints** | **55 tareas** | **~55 horas** | - |

---

## 🚀 PLAN DE EJECUCIÓN RECOMENDADO

### **Semana 1** (15 horas)
- ✅ Sprint 1: Autenticación (3h)
- ✅ Sprint 2: Reservas con calendario (6.5h)
- ✅ Sprint 3: Gestión de servicios - PARCIAL (5h de 7h)

### **Semana 2** (15 horas)
- ✅ Sprint 3: Completar gestión de servicios (2h restantes)
- ✅ Sprint 4: Solicitudes pendientes (5h)
- ✅ Sprint 5: Sistema de pagos (4.5h)
- ✅ Sprint 6: Disponibilidad - PARCIAL (3.5h de 5h)

### **Semana 3** (14 horas)
- ✅ Sprint 6: Completar disponibilidad (1.5h restantes)
- ✅ Sprint 7: Panel de administración (9h)
- ✅ Sprint 10: Seguridad básica (3.5h de 4.5h)

### **Semana 4** (11 horas)
- ✅ Sprint 8: Vistas de usuario (5.5h)
- ✅ Sprint 9: Componentes reutilizables (4.5h)
- ✅ Sprint 10: Completar seguridad (1h restante)

**TOTAL: 4 semanas** (~55 horas de desarrollo)

---

## ✅ CHECKLIST RÁPIDO PARA HOY

### Tareas Inmediatas (Próximas 2-3 horas)

- [ ] **Tarea #1**: Modificar `app/Http/Middleware/RedirectIfAuthenticated.php`
  - Cambiar `return redirect('/dashboard')` por `return redirect('/')`
  
- [ ] **Tarea #2**: Actualizar `config/fortify.php`
  - Cambiar `'home' => '/dashboard'` por `'home' => '/'`
  
- [ ] **Tarea #3**: Crear `resources/views/components/user-dropdown.blade.php`
  - Opciones para Cliente: Perfil, Mis Reservas, Mensajes, Cerrar Sesión
  - Opciones para Profesional: Perfil, Mis Servicios, Solicitudes, Disponibilidad, Mensajes, Cerrar Sesión
  - Opciones para Admin: Panel Admin, Usuarios, Servicios, Reservas, Pagos, Cerrar Sesión
  
- [ ] **Tarea #4**: Integrar dropdown en `resources/views/layouts/marketplace.blade.php`
  - Reemplazar botones de login/register por dropdown cuando esté autenticado
  
- [ ] **Tarea #5**: Crear migración `database/migrations/create_notifications_table.php`
  - Campos: id, user_id, type, title, message, link, read_at, created_at
  
- [ ] **Tarea #6**: Crear modelo `app/Models/Notification.php`
  
- [ ] **Tarea #7**: Instalar Fullcalendar
  ```bash
  npm install @fullcalendar/core @fullcalendar/daygrid @fullcalendar/interaction
  ```

- [ ] **Tarea EXTRA**: Ejecutar migraciones
  ```bash
  php artisan migrate
  ```

---

## 📝 NOTAS

### Estados de las Tareas
- ⏳ **Pendiente**: No iniciada
- 🔄 **En Progreso**: Trabajando actualmente
- ✅ **Completada**: Finalizada y probada
- ⚠️ **Bloqueada**: Depende de otra tarea
- 🐛 **Bug**: Requiere corrección

### Convención de Prioridades
- 🔴 **CRÍTICO**: Funcionalidad core, debe hacerse primero
- 🟡 **IMPORTANTE**: Funcionalidad necesaria, puede esperar un poco
- 🟢 **DESEABLE**: Mejoras, puede posponerse

### Actualizar este documento
Marca las tareas como completadas con: `✅ Completada` cuando termines.
Anota el tiempo real vs estimado para mejorar futuras estimaciones.

---

**Última actualización**: 18 de Noviembre de 2025
**Tareas completadas**: 0/55 (0%)
**Tiempo invertido**: 0 horas
**Tiempo restante estimado**: 55 horas
