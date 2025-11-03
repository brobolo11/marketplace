<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',      // ID del cliente que hace la reserva
        'pro_id',       // ID del profesional contratado
        'service_id',   // ID del servicio reservado
        'datetime',     // Fecha y hora de la cita
        'address',      // Dirección donde se realizará el servicio
        'status',       // Estado: pending, accepted, rejected, completed, cancelled
        'total_price',  // Precio total acordado
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'datetime' => 'datetime',        // Cast a Carbon (fecha y hora)
        'total_price' => 'decimal:2',    // Precio con 2 decimales
    ];

    // ========================================
    // RELACIONES DEL MODELO BOOKING
    // ========================================

    /**
     * Cliente que realizó la reserva.
     * Relación: Una reserva pertenece a un usuario (cliente).
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Profesional contratado para la reserva.
     * Relación: Una reserva pertenece a un usuario (profesional).
     */
    public function professional()
    {
        return $this->belongsTo(User::class, 'pro_id');
    }

    /**
     * Servicio reservado.
     * Relación: Una reserva pertenece a un servicio.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Reseña asociada a esta reserva.
     * Relación: Una reserva puede tener una reseña.
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    /**
     * Verifica si la reserva está pendiente.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica si la reserva fue aceptada.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Verifica si la reserva está completada.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Verifica si la reserva fue cancelada o rechazada.
     */
    public function isCancelled(): bool
    {
        return in_array($this->status, ['cancelled', 'rejected']);
    }

    /**
     * Acepta la reserva (solo el profesional puede hacerlo).
     */
    public function accept(): bool
    {
        $this->status = 'accepted';
        return $this->save();
    }

    /**
     * Rechaza la reserva (solo el profesional puede hacerlo).
     */
    public function reject(): bool
    {
        $this->status = 'rejected';
        return $this->save();
    }

    /**
     * Cancela la reserva (el cliente puede hacerlo).
     */
    public function cancel(): bool
    {
        $this->status = 'cancelled';
        return $this->save();
    }

    /**
     * Marca la reserva como completada.
     */
    public function complete(): bool
    {
        $this->status = 'completed';
        return $this->save();
    }
}
