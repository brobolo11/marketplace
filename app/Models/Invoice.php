<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'invoice_number',
        'issued_at',
        'total',
        'status',
        'pdf_path',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issued_at' => 'datetime',
        'total' => 'decimal:2',
    ];

    /**
     * Relación con la reserva.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Genera un número de factura único.
     */
    public static function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $lastInvoice = self::whereYear('issued_at', $year)
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            // Extraer el número de la última factura
            $lastNumber = (int) substr($lastInvoice->invoice_number, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('INV-%d-%03d', $year, $newNumber);
    }

    /**
     * Verifica si la factura está pagada.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Verifica si la factura está pendiente.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Marca la factura como pagada.
     */
    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }

    /**
     * Marca la factura como cancelada.
     */
    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
