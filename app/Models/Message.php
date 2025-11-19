<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',    // ID del usuario que envía el mensaje
        'receiver_id',  // ID del usuario que recibe el mensaje
        'booking_id',   // ID de la reserva relacionada (opcional)
        'subject',      // Asunto del mensaje (opcional)
        'message',      // Contenido del mensaje
        'read_at',      // Fecha y hora de lectura
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========================================
    // RELACIONES DEL MODELO MESSAGE
    // ========================================

    /**
     * Usuario que envió el mensaje.
     * Relación: Un mensaje pertenece a un usuario (remitente).
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Usuario que recibe el mensaje.
     * Relación: Un mensaje pertenece a un usuario (destinatario).
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Reserva relacionada con el mensaje (opcional).
     * Relación: Un mensaje puede pertenecer a una reserva.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    /**
     * Marca el mensaje como leído.
     */
    public function markAsRead(): bool
    {
        $this->read_at = now();
        return $this->save();
    }

    /**
     * Verifica si el mensaje ha sido leído.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Obtiene los mensajes no leídos de un usuario.
     */
    public static function unreadForUser(int $userId)
    {
        return self::where('receiver_id', $userId)
            ->whereNull('read_at')
            ->get();
    }

    /**
     * Obtiene la conversación entre dos usuarios.
     */
    public static function conversationBetween(int $user1Id, int $user2Id)
    {
        return self::where(function ($query) use ($user1Id, $user2Id) {
            $query->where('sender_id', $user1Id)
                  ->where('receiver_id', $user2Id);
        })->orWhere(function ($query) use ($user1Id, $user2Id) {
            $query->where('sender_id', $user2Id)
                  ->where('receiver_id', $user1Id);
        })->orderBy('created_at', 'asc')->get();
    }
}
