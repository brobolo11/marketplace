<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',        // ID del profesional
        'weekday',        // Día de la semana (0 = Domingo, 1 = Lunes, ..., 6 = Sábado)
        'start_time',     // Hora de inicio (ej: 09:00)
        'end_time',       // Hora de fin (ej: 18:00)
        'specific_date',  // Fecha específica para bloqueos (vacaciones, etc.)
        'is_available',   // true = disponible, false = bloqueado
        'reason',         // Razón del bloqueo
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weekday' => 'integer',
        'specific_date' => 'date',
        'is_available' => 'boolean',
    ];

    // ========================================
    // RELACIONES DEL MODELO AVAILABILITY
    // ========================================

    /**
     * Profesional al que pertenece esta disponibilidad.
     * Relación: Una disponibilidad pertenece a un usuario (profesional).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    /**
     * Array de nombres de días de la semana.
     */
    public static $weekdays = [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
    ];

    /**
     * Obtiene el nombre del día de la semana.
     */
    public function getWeekdayNameAttribute(): string
    {
        return self::$weekdays[$this->weekday] ?? 'Desconocido';
    }

    /**
     * Verifica si un profesional está disponible en un día específico.
     */
    public static function isAvailableOn(int $userId, int $weekday): bool
    {
        return self::where('user_id', $userId)
            ->where('weekday', $weekday)
            ->exists();
    }

    /**
     * Obtiene todos los bloques de disponibilidad de un profesional.
     */
    public static function forProfessional(int $userId)
    {
        return self::where('user_id', $userId)
            ->orderBy('weekday')
            ->orderBy('start_time')
            ->get();
    }
}
