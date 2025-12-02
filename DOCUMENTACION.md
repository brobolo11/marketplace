# 🏠 HouseFixes - Documentación Técnica del Proyecto

**Plataforma de Marketplace para Servicios Profesionales del Hogar**

---

## 📑 Índice

1. [Descripción General](#descripción-general)
2. [Características Principales](#características-principales)
3. [Arquitectura del Sistema](#arquitectura-del-sistema)
4. [Modelos y Relaciones](#modelos-y-relaciones)
5. [Sistema de Roles y Permisos](#sistema-de-roles-y-permisos)
6. [Flujos de Trabajo](#flujos-de-trabajo)
7. [Sistema de Facturas](#sistema-de-facturas)
8. [Configuración y Despliegue](#configuración-y-despliegue)

---

## 📖 Descripción General

HouseFixes es una plataforma web completa que conecta clientes con profesionales de servicios del hogar. Permite la gestión de servicios, reservas, pagos, reseñas, mensajería y un sistema completo de facturación.

### Stack Tecnológico

- **Backend:** Laravel 12.35.0
- **Frontend:** Blade Templates + Tailwind CSS 3.4 + Alpine.js
- **Base de Datos:** MySQL 8.0
- **Autenticación:** Laravel Jetstream + Fortify
- **PDF:** barryvdh/laravel-dompdf v3.1.1
- **Build Tool:** Vite

---

## ✨ Características Principales

### Para Clientes
- Búsqueda y filtrado de servicios por categoría
- Sistema de reservas con selección de fecha
- Gestión de reservas (ver estado, cancelar)
- Sistema de pagos integrado
- Reseñas y valoraciones de servicios
- Chat con profesionales
- Descarga de facturas

### Para Profesionales
- Publicación y gestión de servicios
- Gestión de disponibilidad (horarios semanales y bloqueos específicos)
- Aceptación/rechazo de solicitudes de reserva
- Dashboard con estadísticas de ingresos
- Sistema de facturas automático con generación de PDF
- Chat con clientes
- Galería de fotos por servicio

### Para Administradores
- Panel administrativo completo
- Gestión de usuarios (CRUD, cambio de roles)
- Gestión de categorías
- Supervisión de reservas y servicios
- Gestión de facturas del sistema
- Estadísticas globales en tiempo real

---

## 🏗️ Arquitectura del Sistema

### Estructura de Directorios

```
proyecto-final/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php          # Gestión administrativa
│   │   │   ├── AvailabilityController.php   # Disponibilidad de profesionales
│   │   │   ├── BookingController.php        # Gestión de reservas
│   │   │   ├── CategoryController.php       # Categorías de servicios
│   │   │   ├── InvoiceController.php        # Sistema de facturas
│   │   │   ├── MessageController.php        # Mensajería
│   │   │   ├── PaymentController.php        # Procesamiento de pagos
│   │   │   ├── ProfessionalController.php   # Perfil de profesionales
│   │   │   ├── ReviewController.php         # Sistema de reseñas
│   │   │   └── ServiceController.php        # Gestión de servicios
│   │   └── Middleware/
│   │       ├── EnsureUserIsAdmin.php        # Protección rutas admin
│   │       └── EnsureUserIsProfessional.php # Protección rutas profesional
│   ├── Models/
│   │   ├── Availability.php    # Disponibilidad horaria
│   │   ├── Booking.php         # Reservas
│   │   ├── Category.php        # Categorías
│   │   ├── Invoice.php         # Facturas
│   │   ├── Message.php         # Mensajes
│   │   ├── Notification.php    # Notificaciones
│   │   ├── Payment.php         # Pagos
│   │   ├── Review.php          # Reseñas
│   │   ├── Service.php         # Servicios
│   │   ├── ServicePhoto.php    # Fotos de servicios
│   │   └── User.php            # Usuarios
│   ├── Policies/
│   │   └── ServicePolicy.php   # Políticas de autorización
│   └── Services/
│       └── NotificationService.php # Servicio de notificaciones
├── database/
│   ├── migrations/             # 21 migraciones
│   └── seeders/                # Datos de prueba
├── resources/
│   └── views/
│       ├── admin/              # Vistas administrativas
│       ├── bookings/           # Gestión de reservas
│       ├── categories/         # Categorías
│       ├── components/         # Componentes reutilizables
│       ├── invoices/           # Sistema de facturas
│       ├── messages/           # Mensajería
│       ├── payments/           # Pagos
│       ├── professionals/      # Perfiles profesionales
│       └── services/           # Servicios
└── routes/
    └── web.php                 # Definición de rutas
```

---

## 🗄️ Modelos y Relaciones

### Diagrama de Relaciones

```
User (usuarios)
├── HasMany → Services (como profesional)
├── HasMany → Bookings (como cliente: user_id)
├── HasMany → Bookings (como profesional: pro_id)
├── HasMany → Reviews (como autor)
├── HasMany → Messages (enviados)
├── HasMany → Messages (recibidos)
├── HasMany → Payments (como cliente)
├── HasMany → Payments (como profesional)
└── HasMany → Availabilities

Service (servicios)
├── BelongsTo → User (profesional)
├── BelongsTo → Category
├── HasMany → ServicePhotos
├── HasMany → Bookings
└── HasMany → Reviews

Booking (reservas)
├── BelongsTo → User (cliente)
├── BelongsTo → User (profesional: pro_id)
├── BelongsTo → Service
├── HasOne → Review
├── HasOne → Payment
└── HasOne → Invoice

Invoice (facturas)
└── BelongsTo → Booking

Payment (pagos)
├── BelongsTo → Booking
├── BelongsTo → User (cliente)
└── BelongsTo → User (profesional)

Review (reseñas)
├── BelongsTo → User (autor)
├── BelongsTo → Service
└── BelongsTo → Booking

Message (mensajes)
├── BelongsTo → User (sender)
└── BelongsTo → User (receiver)

Category (categorías)
└── HasMany → Services

Availability (disponibilidad)
└── BelongsTo → User (profesional)
```

### Estados de las Reservas

- **pending**: Solicitud creada, esperando respuesta del profesional
- **accepted**: Profesional aceptó, pendiente de pago
- **rejected**: Profesional rechazó la solicitud
- **completed**: Servicio completado
- **cancelled**: Cancelada por cliente o profesional

### Estados de las Facturas

- **pending**: Factura generada, pago pendiente
- **paid**: Factura pagada
- **cancelled**: Factura cancelada

### Estados de los Pagos

- **pending**: Pago iniciado
- **processing**: Procesando pago
- **completed**: Pago completado exitosamente
- **failed**: Pago fallido
- **refunded**: Pago reembolsado

---

## 🔐 Sistema de Roles y Permisos

### Roles de Usuario

```php
User::ROLE_ADMIN = 'admin';
User::ROLE_PROFESSIONAL = 'professional';
User::ROLE_CLIENT = 'client';
```

### Métodos de Verificación

```php
$user->isAdmin()        // Verifica si es administrador
$user->isPro()          // Verifica si es profesional
$user->isClient()       // Verifica si es cliente
```

### Middlewares

```php
'admin'        // Requiere rol de administrador
'professional' // Requiere rol de profesional
'auth'         // Requiere autenticación (cualquier rol)
```

### Protección de Rutas

```php
// Solo administradores
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'users']);
});

// Solo profesionales
Route::middleware(['auth', 'professional'])->group(function () {
    Route::post('/services', [ServiceController::class, 'store']);
    Route::get('/availability', [AvailabilityController::class, 'index']);
});

// Usuarios autenticados
Route::middleware(['auth'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/messages', [MessageController::class, 'index']);
});
```

---

## 🔄 Flujos de Trabajo

### Flujo de Reserva Completo

```
1. Cliente busca servicio
   └─> GET /services
   
2. Cliente ve detalles del servicio
   └─> GET /services/{id}
   
3. Cliente selecciona fecha y crea reserva
   └─> POST /bookings
       - Estado: pending
       - Notificación al profesional
   
4. Profesional revisa solicitud
   └─> GET /bookings/pending
   
5a. Profesional ACEPTA
    └─> POST /bookings/{id}/approve
        - Estado: accepted
        - Notificación al cliente (pago requerido)
        
5b. Profesional RECHAZA
    └─> POST /bookings/{id}/reject
        - Estado: rejected
        - Notificación al cliente
        - FIN del flujo
   
6. Cliente procesa pago
   └─> GET /bookings/{id}/checkout
   └─> POST /payments
       - Pago completado
       - Notificación al profesional
   
7. Profesional completa el servicio
   └─> POST /bookings/{id}/complete
       - Estado: completed
       - Habilita opción de generar factura
   
8. Cliente deja reseña
   └─> POST /reviews
       - Rating y comentario
   
9. Profesional genera factura
   └─> POST /bookings/{id}/generate-invoice
       - Crea registro Invoice
       - Genera PDF automáticamente
       - Notificación al cliente
   
10. Cliente descarga factura
    └─> GET /invoices/{id}/download
```

### Flujo de Gestión de Servicios

```
1. Profesional crea servicio
   └─> POST /services
       - Título, descripción, precio, categoría
       - Sube fotos (opcional)
   
2. Servicio aparece en listado público
   └─> GET /services
       - Visible para todos los usuarios
   
3. Profesional edita servicio
   └─> PUT /services/{id}
       - Actualiza información
       - Agrega/elimina fotos
   
4. Clientes ven el servicio y dejan reseñas
   └─> Promedio de rating se actualiza
   
5. Profesional puede eliminar servicio
   └─> DELETE /services/{id}
       - Solo si no tiene reservas activas
```

---

## 📄 Sistema de Facturas

### Características

- Generación automática de número de factura (formato: INV-YYYY-XXX)
- Creación de PDF con diseño profesional
- Cálculo automático de IVA (21%)
- Descarga de facturas en PDF
- Estados: pending, paid, cancelled
- Filtrado por estado
- Estadísticas de facturación

### Estructura de Factura

**Campos del PDF:**
- Número de factura único
- Fecha de emisión
- Información del profesional (nombre, email, teléfono)
- Información del cliente (nombre, email, teléfono)
- Detalles del servicio (nombre, descripción, categoría)
- Fecha y hora del servicio
- Dirección donde se realizó el servicio
- Tabla de conceptos con descripción y precio
- Cálculo de subtotal, IVA (21%) y total
- Estado de la factura (badge coloreado)

### Generación de PDFs

**Paquete:** barryvdh/laravel-dompdf v3.1.1

**Vista:** `resources/views/invoices/pdf.blade.php`

**Almacenamiento:** `storage/app/invoices/{invoice_number}.pdf`

**Proceso:**
1. Profesional completa un servicio
2. Click en "Generar Factura"
3. Sistema crea registro en tabla `invoices`
4. Genera PDF automáticamente usando dompdf
5. Guarda PDF en storage
6. Redirige a vista de factura
7. Cliente y profesional pueden descargar el PDF

### Métodos Principales del Controlador

```php
// Listar facturas (profesionales ven las generadas, clientes las recibidas, admin ve todas)
InvoiceController@index()

// Ver detalle de factura
InvoiceController@show(Invoice $invoice)

// Generar nueva factura
InvoiceController@generate(Booking $booking)

// Descargar PDF (regenera si no existe)
InvoiceController@download(Invoice $invoice)

// Marcar como pagada
InvoiceController@markAsPaid(Invoice $invoice)
```

### Vista Administrativa de Facturas

Los administradores tienen acceso a una vista especial (`invoices/admin.blade.php`) que muestra:

- Todas las facturas del sistema
- Columnas para profesional y cliente
- Estadísticas globales (total facturas, total facturado, pagado, pendiente)
- Búsqueda por número de factura, cliente o profesional
- Filtrado por estado
- Acciones: ver detalle y descargar PDF

---

## ⚙️ Configuración y Despliegue

### Requisitos del Sistema

- PHP >= 8.2
- Composer >= 2.6
- Node.js >= 18.x
- NPM >= 9.x
- MySQL >= 8.0
- Extensiones PHP: PDO, mbstring, openssl, xml, gd

### Instalación

```bash
# Clonar repositorio
git clone https://github.com/brobolo11/marketplace.git
cd marketplace

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
DB_DATABASE=housefixes
DB_USERNAME=root
DB_PASSWORD=

# Ejecutar migraciones y seeders
php artisan migrate --seed

# Crear enlace simbólico para storage
php artisan storage:link

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

### Usuarios de Prueba

Después de ejecutar los seeders:

**Administrador:**
- Email: admin@housefixes.com
- Password: password

**Profesionales:**
- carlos@profesionales.com (Fontanero)
- maria@profesionales.com (Electricista)
- laura@profesionales.com (Limpieza)
- pedro@profesionales.com (Carpintero)
- Password: password (todos)

**Clientes:**
- roberto@clientes.com
- elena@clientes.com
- francisco@clientes.com
- Password: password (todos)

### Comandos Útiles

```bash
# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Regenerar autoload
composer dump-autoload

# Ver rutas registradas
php artisan route:list

# Ver estado de migraciones
php artisan migrate:status
```

### Variables de Entorno Importantes

```env
APP_NAME="HouseFixes"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=housefixes

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025

# Para producción con Stripe (ejemplo)
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
```

---

## 📊 Estadísticas del Proyecto

### Base de Datos

- **Migraciones:** 21 tablas
- **Seeders:** 8 archivos
- **Relaciones:** 20+ definidas

### Código

- **Modelos:** 12 archivos
- **Controladores:** 10 archivos
- **Vistas:** 50+ archivos blade
- **Middlewares:** 2 personalizados
- **Rutas:** 80+ definidas

### Funcionalidades

- ✅ Sistema de autenticación completo (2FA incluido)
- ✅ Gestión de usuarios (3 roles)
- ✅ CRUD de servicios con fotos
- ✅ Sistema de reservas completo
- ✅ Sistema de pagos (simulado)
- ✅ Sistema de facturas con PDF
- ✅ Sistema de reseñas y ratings
- ✅ Mensajería entre usuarios
- ✅ Panel administrativo
- ✅ Gestión de disponibilidad
- ✅ Dashboard con estadísticas

---

## 🔧 Mantenimiento

### Logs

Los logs se encuentran en: `storage/logs/laravel.log`

### Backup de Base de Datos

```bash
# Exportar
mysqldump -u root housefixes > backup.sql

# Importar
mysql -u root housefixes < backup.sql
```

### Actualización de Dependencias

```bash
# Backend
composer update

# Frontend
npm update

# Verificar actualizaciones
composer outdated
npm outdated
```

---

## 📞 Soporte y Contribución

**Repositorio:** https://github.com/brobolo11/marketplace

**Autor:** brobolo11

**Licencia:** MIT

---

**Última actualización:** Diciembre 2025  
**Versión:** 1.0.0  
**Estado:** ✅ Producción
