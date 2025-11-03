# 🔐 CREDENCIALES DEL SISTEMA - SERVICIOS PRO

## Usuarios de Prueba

### 👨‍💼 ADMINISTRADOR
- **Email:** admin@servicios.com
- **Contraseña:** password
- **Rol:** admin
- **Acceso:** Panel administrativo completo
- **URL Dashboard:** http://localhost/admin/dashboard

### 👷 PROFESIONALES
1. **Carlos Rodríguez** (Fontanero)
   - Email: carlos@profesionales.com
   - Contraseña: password
   - Ciudad: Madrid

2. **María García** (Electricista)
   - Email: maria@profesionales.com
   - Contraseña: password
   - Ciudad: Barcelona

3. **Juan Martínez** (Jardinero)
   - Email: juan@profesionales.com
   - Contraseña: password
   - Ciudad: Valencia

4. **Ana López** (Profesora)
   - Email: ana@profesionales.com
   - Contraseña: password
   - Ciudad: Madrid

5. **Laura Sánchez** (Limpieza)
   - Email: laura@profesionales.com
   - Contraseña: password
   - Ciudad: Sevilla

### 👤 CLIENTES
Ver UserSeeder.php para más clientes de prueba

---

## Roles y Permisos

### 🔴 ADMINISTRADOR (admin)
**Acceso Completo al Sistema**

✅ Funcionalidades:
- Dashboard administrativo con estadísticas generales
- Gestión completa de usuarios (ver, editar rol, eliminar)
- Visualización de todas las reservas del sistema
- Acceso a todos los servicios publicados
- Estadísticas de ingresos y métricas
- Cambio de roles de usuarios
- Eliminación de usuarios (excepto a sí mismo)

📍 Rutas protegidas con middleware `admin`:
- /admin/dashboard
- /admin/users
- /admin/users/{user}
- /admin/users/{user}/role (PATCH)
- /admin/users/{user} (DELETE)

### 🔵 PROFESIONAL (pro)
**Gestión de Servicios y Reservas**

✅ Funcionalidades:
- Crear, editar y eliminar servicios propios
- Gestionar disponibilidad horaria
- Recibir y gestionar reservas (aceptar/rechazar/completar)
- Ver reseñas recibidas
- Enviar/recibir mensajes
- Perfil público visible

📍 Rutas protegidas con middleware `professional`:
- /services/create
- /services/{service}/edit
- /availability/*

### 🟢 CLIENTE (client)
**Búsqueda y Reserva de Servicios**

✅ Funcionalidades:
- Buscar servicios y profesionales
- Realizar reservas de servicios
- Cancelar reservas propias (si están pendientes)
- Dejar reseñas de servicios completados
- Enviar/recibir mensajes
- Ver historial de reservas

📍 Rutas públicas y protegidas con `auth`:
- /services (búsqueda)
- /bookings/store (crear reserva)
- /reviews/create (dejar reseña)
- /messages

---

## Middlewares Implementados

### 1. `admin` - EnsureUserIsAdmin
```php
// Verifica que el usuario autenticado sea administrador
if (!auth()->check() || !auth()->user()->isAdmin()) {
    abort(403, 'Acceso denegado. Solo administradores.');
}
```

### 2. `professional` - EnsureUserIsProfessional
```php
// Verifica que el usuario autenticado sea profesional
if (!auth()->check() || !auth()->user()->isPro()) {
    abort(403, 'Acceso denegado. Solo profesionales.');
}
```

### 3. `auth` - Autenticación Laravel
```php
// Verifica que el usuario esté autenticado (Jetstream)
```

---

## Métodos de Verificación de Roles (User Model)

```php
// Verificar si es cliente
$user->isClient() // returns true/false

// Verificar si es profesional  
$user->isPro() // returns true/false

// Verificar si es administrador
$user->isAdmin() // returns true/false
```

---

## Dashboards Diferenciados

### ADMIN Dashboard
- **Vista:** resources/views/admin/dashboard.blade.php
- **Ruta:** /admin/dashboard
- **Estadísticas:**
  - Total usuarios (clientes, profesionales, admins)
  - Total servicios publicados
  - Total reservas y pendientes
  - Ingresos del mes
  - Últimos usuarios registrados
  - Últimas reservas
  - Servicios más populares
  - Profesionales mejor valorados

### PROFESIONAL Dashboard  
- **Vista:** resources/views/dashboard.blade.php
- **Accesos rápidos:**
  - Crear nuevo servicio
  - Gestionar mis servicios
  - Configurar disponibilidad
  - Ver reservas recibidas
  - Mensajes de clientes

### CLIENTE Dashboard
- **Vista:** resources/views/dashboard.blade.php
- **Accesos rápidos:**
  - Buscar servicios
  - Explorar profesionales
  - Navegar categorías
  - Ver mis reservas
  - Mensajes con profesionales

---

## Sistema de Sesiones

✅ **Implementación:** Laravel Jetstream con Fortify
✅ **Autenticación:** session + sanctum
✅ **Protección CSRF:** Habilitada
✅ **Remember Token:** Implementado
✅ **Email Verification:** Configurado (opcional)
✅ **Two Factor Auth:** Disponible (Jetstream)

### Configuración de Sesión
- **Driver:** file (puede cambiar a database/redis en producción)
- **Lifetime:** 120 minutos
- **Archivo:** config/session.php

---

## Seguridad Implementada

### Protección de Rutas
```php
// Solo admin
Route::middleware(['auth', 'admin'])->group(...);

// Solo profesionales
Route::middleware(['auth', 'professional'])->group(...);

// Usuarios autenticados
Route::middleware(['auth'])->group(...);
```

### Validaciones en Controladores
- Verificación de propietario en ServiceController
- Verificación de rol en BookingController
- Verificación de permisos en ReviewController
- Protección contra auto-eliminación en UserController (admin)

---

## Recomendaciones de Seguridad

### Para Producción:
1. ✅ Cambiar contraseñas por defecto
2. ✅ Configurar APP_ENV=production
3. ✅ Habilitar HTTPS
4. ✅ Configurar rate limiting
5. ✅ Usar sesiones en database/redis
6. ✅ Habilitar email verification
7. ✅ Configurar logs de auditoría
8. ✅ Backup automático de base de datos

---

## Próximos Pasos

### Testing Recomendado:
1. ✅ Probar login con cada rol
2. ✅ Verificar middlewares funcionan correctamente
3. ✅ Comprobar permisos de cada dashboard
4. ✅ Validar flujo completo: reserva → aceptación → completar → reseña
5. ✅ Probar gestión de usuarios desde panel admin
6. ✅ Verificar que usuarios no puedan acceder a rutas no autorizadas

---

**Última actualización:** 3 de noviembre de 2025
**Versión:** Laravel 11.x con Jetstream
