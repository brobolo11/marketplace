<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\PaymentController;

// ========================================
// RUTAS PÚBLICAS
// ========================================

/**
 * Página principal / home
 * Muestra categorías destacadas y profesionales con mejor calificación
 */
Route::get('/', function () {
    $categories = \App\Models\Category::limit(12)->get();
    $professionals = \App\Models\User::where('role', 'pro')
        ->with(['services.category'])
        ->limit(8)
        ->get();
    
    return view('home', compact('categories', 'professionals'));
})->name('home');

// ========================================
// RUTAS DE CATEGORÍAS
// ========================================

/**
 * Listar todas las categorías
 * GET /categories
 */
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

/**
 * Ver servicios de una categoría específica
 * GET /categories/{category}/services
 */
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// ========================================
// RUTAS DE SERVICIOS
// ========================================

/**
 * Listar todos los servicios (con filtros)
 * GET /services
 */
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');

/**
 * Ver detalle de un servicio
 * GET /services/{service}
 */
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// ========================================
// RUTAS DE PROFESIONALES
// ========================================

/**
 * Listar todos los profesionales
 * GET /professionals
 */
Route::get('/professionals', [ProfessionalController::class, 'index'])->name('professionals.index');

/**
 * Ver perfil de un profesional
 * GET /professionals/{professional}
 */
Route::get('/professionals/{professional}', [ProfessionalController::class, 'show'])->name('professionals.show');

/**
 * Ver servicios de un profesional
 * GET /professionals/{professional}/services
 */
Route::get('/professionals/{professional}/services', [ProfessionalController::class, 'services'])->name('professionals.services');

/**
 * Ver disponibilidad de un profesional
 * GET /professionals/{professional}/availability
 */
Route::get('/professionals/{professional}/availability', [ProfessionalController::class, 'availability'])->name('professionals.availability');

/**
 * Ver reseñas de un profesional
 * GET /professionals/{professional}/reviews
 */
Route::get('/professionals/{professional}/reviews', [ProfessionalController::class, 'reviews'])->name('professionals.reviews');

// ========================================
// RUTAS JSON PARA DISPONIBILIDAD (públicas)
// ========================================

/**
 * Obtener disponibilidad del profesional por día
 */
Route::get('/json/professional/{professional}/availability', function($professionalId, Illuminate\Http\Request $request) {
    $day = $request->query('day');
    
    $schedules = \App\Models\Availability::where('user_id', $professionalId)
        ->where('weekday', $day)
        ->whereNull('specific_date')
        ->orderBy('start_time')
        ->get(['start_time', 'end_time']);
    
    return response()->json([
        'schedules' => $schedules
    ]);
});

/**
 * Obtener reservas del profesional en una fecha
 */
Route::get('/json/professional/{professional}/bookings', function($professionalId, Illuminate\Http\Request $request) {
    $date = $request->query('date');
    
    $bookings = \App\Models\Booking::where('pro_id', $professionalId)
        ->whereDate('datetime', $date)
        ->whereIn('status', ['pending', 'accepted'])
        ->get();
    
    $bookedTimes = $bookings->map(function($booking) {
        return \Carbon\Carbon::parse($booking->datetime)->format('H:i');
    })->toArray();
    
    return response()->json([
        'booked_times' => $bookedTimes
    ]);
});

// ========================================
// RUTAS PROTEGIDAS (requieren autenticación)
// ========================================

Route::middleware(['auth'])->group(function () {
    
    // ========================================
    // RUTAS DE SERVICIOS (para profesionales)
    // ========================================
    
    /**
     * Formulario para crear un servicio
     * GET /services/create
     */
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    
    /**
     * Guardar un nuevo servicio
     * POST /services
     */
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    
    /**
     * Formulario para editar un servicio
     * GET /services/{service}/edit
     */
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    
    /**
     * Actualizar un servicio
     * PUT /services/{service}
     */
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    
    /**
     * Eliminar un servicio
     * DELETE /services/{service}
     */
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // ========================================
    // RUTAS DE RESERVAS (BOOKINGS)
    // ========================================
    
    /**
     * Listar reservas del usuario (cliente o profesional)
     * GET /bookings
     */
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    
    /**
     * Ver solicitudes pendientes (solo profesionales)
     * GET /bookings/pending-requests
     */
    Route::get('/bookings/pending-requests', [BookingController::class, 'pendingRequests'])->name('bookings.pendingRequests');
    
    /**
     * Ver detalle de una reserva
     * GET /bookings/{booking}
     */
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    
    /**
     * Crear una nueva reserva
     * POST /bookings
     */
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    
    /**
     * Profesional aprueba una reserva
     * POST /bookings/{booking}/approve
     */
    Route::post('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    
    /**
     * Profesional rechaza una reserva
     * POST /bookings/{booking}/reject
     */
    Route::post('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    
    /**
     * Cliente cancela una reserva
     * PATCH /bookings/{booking}/cancel
     */
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    /**
     * Profesional marca una reserva como completada
     * PATCH /bookings/{booking}/complete
     */
    Route::patch('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');

    // ========================================
    // RUTAS DE RESEÑAS (REVIEWS)
    // ========================================
    
    /**
     * Formulario para crear una reseña
     * GET /bookings/{booking}/review/create
     */
    Route::get('/bookings/{booking}/review/create', [ReviewController::class, 'create'])->name('reviews.create');
    
    /**
     * Guardar una nueva reseña
     * POST /reviews
     */
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    
    /**
     * Eliminar una reseña
     * DELETE /reviews/{review}
     */
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // ========================================
    // RUTAS DE MENSAJES (CHAT)
    // ========================================
    
    /**
     * Listar conversaciones del usuario
     * GET /messages
     */
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    
    /**
     * Ver conversación con un usuario específico
     * GET /messages/{user}
     */
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    
    /**
     * Enviar un mensaje
     * POST /messages
     */
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    
    /**
     * Obtener mensajes no leídos (API)
     * GET /messages/unread
     */
    Route::get('/messages/unread', [MessageController::class, 'unread'])->name('messages.unread');
    
    /**
     * Marcar mensaje como leído
     * PATCH /messages/{message}/read
     */
    Route::patch('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');

    // ========================================
    // RUTAS DE DISPONIBILIDAD (para profesionales)
    // ========================================
    
    /**
     * Ver disponibilidad del profesional autenticado
     * GET /availability
     */
    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
    
    /**
     * Crear un bloque de disponibilidad
     * POST /availability
     */
    Route::post('/availability', [AvailabilityController::class, 'store'])->name('availability.store');
    
    /**
     * Actualizar un bloque de disponibilidad
     * PUT /availability/{availability}
     */
    Route::put('/availability/{availability}', [AvailabilityController::class, 'update'])->name('availability.update');
    
    /**
     * Eliminar un bloque de disponibilidad
     * DELETE /availability/{availability}
     */
    Route::delete('/availability/{availability}', [AvailabilityController::class, 'destroy'])->name('availability.destroy');
    
    /**
     * Crear un bloqueo específico (vacaciones, día festivo)
     * POST /availability/block
     */
    Route::post('/availability/block', [AvailabilityController::class, 'createBlock'])->name('availability.block');
    
    /**
     * Eliminar un bloqueo específico
     * DELETE /availability/block/{id}
     */
    Route::delete('/availability/block/{id}', [AvailabilityController::class, 'deleteBlock'])->name('availability.block.delete');

    // ========================================
    // RUTAS DE PAGOS (SIMULADOS)
    // ========================================
    
    /**
     * Historial de pagos del usuario
     * GET /payments
     */
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    
    /**
     * Página de checkout para una reserva
     * GET /bookings/{booking}/checkout
     */
    Route::get('/bookings/{booking}/checkout', [PaymentController::class, 'checkout'])->name('bookings.checkout');
    
    /**
     * Procesar pago simulado
     * POST /payments
     */
    Route::post('/payments', [PaymentController::class, 'process'])->name('payments.process');
    
    /**
     * Confirmación de pago
     * GET /payments/{payment}/confirmation
     */
    Route::get('/payments/{payment}/confirmation', [PaymentController::class, 'confirmation'])->name('payments.confirmation');
    
    /**
     * Solicitar reembolso
     * POST /payments/{payment}/refund
     */
    Route::post('/payments/{payment}/refund', [PaymentController::class, 'refund'])->name('payments.refund');
    
    /**
     * Ver/Descargar recibo
     * GET /payments/{payment}/receipt
     */
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');

});

// ========================================
// RUTAS DE ADMINISTRACIÓN
// Solo accesibles por usuarios con rol 'admin'
// ========================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    /**
     * Dashboard administrativo
     * GET /admin/dashboard
     */
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    /**
     * Gestión de usuarios
     * GET /admin/users
     */
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users.index');
    Route::patch('/users/{user}/role', [App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.destroy');
    
    /**
     * Gestión de servicios
     * GET /admin/services
     */
    Route::get('/services', [App\Http\Controllers\AdminController::class, 'services'])->name('services.index');
    Route::delete('/services/{service}', [App\Http\Controllers\AdminController::class, 'deleteService'])->name('services.destroy');
    
    /**
     * Gestión de reservas
     * GET /admin/bookings
     */
    Route::get('/bookings', [App\Http\Controllers\AdminController::class, 'bookings'])->name('bookings.index');
    
    /**
     * Gestión de categorías
     * GET /admin/categories
     */
    Route::get('/categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [App\Http\Controllers\AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [App\Http\Controllers\AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\AdminController::class, 'deleteCategory'])->name('categories.destroy');
    
});

// ========================================
// RUTAS DE AUTENTICACIÓN
// (Se deben agregar con Laravel Breeze/Jetstream)
// ========================================
// require __DIR__.'/auth.php';

// Redirección después del login según el rol del usuario
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        // Redirigir según el rol del usuario
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->isPro()) {
            return redirect()->route('profile.show'); // Dashboard profesional
        } else {
            return redirect()->route('home'); // Cliente va a inicio
        }
    })->name('dashboard');
});
