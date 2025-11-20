<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',       // ID del profesional que ofrece el servicio
        'category_id',   // ID de la categoría del servicio
        'title',         // Título del servicio
        'description',   // Descripción detallada
        'price_hour',    // Precio por hora
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_hour' => 'decimal:2',  // Precio con 2 decimales
    ];

    // ========================================
    // RELACIONES DEL MODELO SERVICE
    // ========================================

    /**
     * Profesional que ofrece este servicio.
     * Relación: Un servicio pertenece a un usuario (profesional).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Categoría a la que pertenece el servicio.
     * Relación: Un servicio pertenece a una categoría.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Fotos del portafolio del servicio.
     * Relación: Un servicio puede tener muchas fotos.
     */
    public function photos()
    {
        return $this->hasMany(ServicePhoto::class);
    }

    /**
     * Reservas realizadas para este servicio.
     * Relación: Un servicio puede tener muchas reservas.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Reseñas del servicio a través de las reservas.
     * Relación: Un servicio tiene muchas reseñas a través de las reservas.
     */
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Booking::class, 'service_id', 'booking_id');
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    /**
     * Obtiene el número de reservas completadas para este servicio.
     */
    public function completedBookingsCount(): int
    {
        return $this->bookings()->where('status', 'completed')->count();
    }

    /**
     * Calcula la calificación promedio del servicio basado en las reseñas.
     */
    public function averageRating(): float
    {
        $bookingIds = $this->bookings()->pluck('id');
        return Review::whereIn('booking_id', $bookingIds)->avg('rating') ?? 0;
    }
}
