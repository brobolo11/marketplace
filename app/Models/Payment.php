<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'user_id',
        'professional_id',
        'amount',
        'platform_fee',
        'professional_amount',
        'payment_method',
        'transaction_id',
        'status',
        'notes',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'professional_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // ========================================
    // RELACIONES DEL MODELO PAYMENT
    // ========================================

    /**
     * Reserva asociada al pago.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Cliente que realizó el pago.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Profesional que recibe el pago.
     */
    public function professional()
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    /**
     * Verifica si el pago está pendiente.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica si el pago está procesando.
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Verifica si el pago está completado.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Verifica si el pago falló.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Verifica si el pago fue reembolsado.
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * Marca el pago como completado.
     */
    public function markAsCompleted(): bool
    {
        $this->status = 'completed';
        $this->paid_at = now();
        return $this->save();
    }

    /**
     * Marca el pago como fallido.
     */
    public function markAsFailed(string $reason = null): bool
    {
        $this->status = 'failed';
        if ($reason) {
            $this->notes = $reason;
        }
        return $this->save();
    }

    /**
     * Marca el pago como reembolsado.
     */
    public function markAsRefunded(): bool
    {
        $this->status = 'refunded';
        return $this->save();
    }

    /**
     * Genera un ID de transacción único.
     */
    public static function generateTransactionId(): string
    {
        return 'TXN-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);
    }

    /**
     * Calcula la comisión de plataforma (10% por defecto).
     */
    public static function calculatePlatformFee(float $amount, float $percentage = 10): float
    {
        return round($amount * ($percentage / 100), 2);
    }

    /**
     * Calcula el monto que recibe el profesional.
     */
    public static function calculateProfessionalAmount(float $amount, float $platformFee): float
    {
        return round($amount - $platformFee, 2);
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Scope para pagos completados.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope para pagos pendientes.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para pagos de un usuario específico.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para pagos recibidos por un profesional.
     */
    public function scopeByProfessional($query, int $professionalId)
    {
        return $query->where('professional_id', $professionalId);
    }
}
