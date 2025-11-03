<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',  // ID de la reserva asociada
        'user_id',     // ID del cliente que escribe la reseña
        'pro_id',      // ID del profesional que recibe la reseña
        'rating',      // Puntuación (1-5 estrellas)
        'comment',     // Comentario opcional
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',  // Rating como entero
    ];

    // ========================================
    // RELACIONES DEL MODELO REVIEW
    // ========================================

    /**
     * Reserva asociada a esta reseña.
     * Relación: Una reseña pertenece a una reserva.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Cliente que escribió la reseña.
     * Relación: Una reseña pertenece a un usuario (cliente).
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Profesional que recibe la reseña.
     * Relación: Una reseña pertenece a un usuario (profesional).
     */
    public function professional()
    {
        return $this->belongsTo(User::class, 'pro_id');
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    /**
     * Obtiene las estrellas en formato visual (★★★★★).
     */
    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Verifica si la reseña tiene un comentario.
     */
    public function hasComment(): bool
    {
        return !empty($this->comment);
    }
}
