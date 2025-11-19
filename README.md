# 🏠 HouseFixes - Marketplace de Servicios Profesionales

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.4-38bdf8.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

Plataforma web completa para conectar clientes con profesionales de servicios del hogar. Sistema de reservas, pagos, reseñas y gestión administrativa integrada.

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Configuración](#️-configuración)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Uso](#-uso)
- [Testing](#-testing)
- [Tecnologías](#-tecnologías)

## ✨ Características

### Para Clientes
- 🔍 Búsqueda y filtrado de servicios por categoría
- 📅 Sistema de reservas con calendario en tiempo real
- 💳 Pasarela de pagos integrada (Stripe)
- ⭐ Sistema de reseñas y valoraciones
- 💬 Chat directo con profesionales
- 📊 Historial de reservas y servicios

### Para Profesionales
- 🛠️ Gestión completa de servicios ofrecidos
- 📆 Control de disponibilidad (horarios semanales y bloqueos específicos)
- 🔔 Notificaciones de nuevas solicitudes
- 💰 Dashboard de ingresos y estadísticas
- 📸 Galería de fotos por servicio
- ⏰ Gestión de solicitudes pendientes

### Para Administradores
- 👥 Gestión de usuarios (clientes, profesionales, admins)
- 🏷️ CRUD de categorías de servicios
- 📊 Panel de estadísticas en tiempo real
- 📋 Supervisión de reservas y servicios
- 🔧 Herramientas de moderación

## 💻 Requisitos

- PHP >= 8.2
- Composer >= 2.6
- Node.js >= 18.x & NPM >= 9.x
- MySQL >= 8.0
- Servidor web (Apache/Nginx) o Laravel Valet/Herd

## 🚀 Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/brobolo11/marketplace.git
cd marketplace
```

### 2. Instalar dependencias de PHP
```bash
composer install
```

### 3. Instalar dependencias de Node.js
```bash
npm install
```

### 4. Configurar variables de entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configurar base de datos
Edita el archivo `.env` y configura tu base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=housefixes
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Ejecutar migraciones y seeders
```bash
php artisan migrate --seed
```

### 7. Crear enlace simbólico para storage
```bash
php artisan storage:link
```

### 8. Compilar assets
```bash
npm run build
# O para desarrollo:
npm run dev
```

### 9. Iniciar el servidor
```bash
php artisan serve
```

Accede a la aplicación en: `http://localhost:8000`

## ⚙️ Configuración

### Usuarios de Prueba
Después de ejecutar los seeders, puedes usar estos usuarios:

**Administrador:**
- Email: admin@housefixes.com
- Password: password

**Profesional:**
- Email: pro@housefixes.com  
- Password: password

**Cliente:**
- Email: client@housefixes.com
- Password: password

### Configurar Pagos (Stripe)
1. Obtén tus claves API de [Stripe Dashboard](https://dashboard.stripe.com)
2. Añade a tu `.env`:
```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

## 📁 Estructura del Proyecto

```
proyecto-final/
├── app/
│   ├── Http/Controllers/      # Controladores
│   ├── Models/                # Modelos Eloquent
│   ├── Policies/              # Políticas de autorización
│   └── Services/              # Servicios de lógica de negocio
├── database/
│   ├── migrations/            # Migraciones de BD (21 archivos)
│   └── seeders/               # Seeders de datos
├── resources/
│   ├── views/                 # Vistas Blade
│   │   ├── admin/            # Panel administrativo
│   │   ├── bookings/         # Reservas
│   │   ├── services/         # Servicios
│   │   ├── messages/         # Mensajería
│   │   └── components/       # Componentes reutilizables
│   ├── css/                   # Estilos Tailwind
│   └── js/                    # JavaScript/Alpine.js
├── routes/
│   ├── web.php               # Rutas web (360+ líneas)
│   └── api.php               # Rutas API
└── tests/                    # Tests automatizados
```

## 📖 Uso

### Flujo de Trabajo Principal

1. **Cliente busca servicio** → Navega por categorías o búsqueda
2. **Selecciona servicio** → Ve detalles, fotos, reseñas
3. **Reserva en calendario** → Elige fechas disponibles
4. **Profesional recibe solicitud** → Notificación automática
5. **Profesional acepta/rechaza** → Cliente es notificado
6. **Servicio completado** → Cliente puede dejar reseña
7. **Pago procesado** → Sistema registra transacción

### Comandos Útiles

```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Regenerar autoload
composer dump-autoload

# Ver rutas
php artisan route:list

# Crear nuevo controlador
php artisan make:controller NombreController

# Crear nueva migración
php artisan make:migration nombre_migracion
```

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests con coverage
php artisan test --coverage

# Ejecutar tests específicos
php artisan test --filter NombreTest
```

## 🛠️ Tecnologías

### Backend
- **Laravel 12.x** - Framework PHP
- **Laravel Jetstream** - Autenticación y equipos
- **Laravel Fortify** - Backend de autenticación
- **MySQL 8.0** - Base de datos
- **Eloquent ORM** - Gestión de base de datos

### Frontend
- **Blade Templates** - Motor de plantillas
- **Tailwind CSS 3.4** - Framework CSS utility-first
- **Alpine.js** - JavaScript reactivo ligero
- **Font Awesome** - Iconos
- **Vite** - Build tool moderno

### Integraciones
- **Stripe** - Pasarela de pagos
- **Laravel Storage** - Gestión de archivos
- **Laravel Notifications** - Sistema de notificaciones

## 📝 Sprints Completados

- ✅ Sprint 1: Authentication & Navigation
- ✅ Sprint 2: Booking Calendar System
- ✅ Sprint 3: Service Management
- ✅ Sprint 4: Pending Requests
- ✅ Sprint 5: Payment System
- ✅ Sprint 6: Availability Management
- ✅ Sprint 7: Review System
- ✅ Sprint 8: Messaging System
- ✅ Sprint 9: Admin Dashboard
- ✅ Sprint 10: Testing & Polish

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.

## 👨‍💻 Autor

**brobolo11**
- GitHub: [@brobolo11](https://github.com/brobolo11)

## 🙏 Agradecimientos

- Laravel Framework
- Tailwind CSS Team
- Alpine.js Community
- Font Awesome

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
