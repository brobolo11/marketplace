<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'city',
        'lat',
        'lng',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========================================
    // RELACIONES
    // ========================================

    /**
     * Servicios que ofrece el profesional
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    /**
     * Reservas realizadas por el cliente
     */
    public function bookingsAsClient()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Reservas recibidas por el profesional
     */
    public function bookingsAsPro()
    {
        return $this->hasMany(Booking::class, 'pro_id');
    }

    /**
     * Reseñas escritas por el usuario
     */
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    /**
     * Reseñas recibidas por el profesional
     */
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'pro_id');
    }

    /**
     * Mensajes enviados
     */
    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Mensajes recibidos
     */
    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Disponibilidad horaria del profesional
     */
    public function availability()
    {
        return $this->hasMany(Availability::class);
    }

    // ========================================
    // MÉTODOS HELPER
    // ========================================

    /**
     * Verifica si el usuario es cliente
     */
    public function isClient()
    {
        return $this->role === 'client';
    }

    /**
     * Verifica si el usuario es profesional
     */
    public function isPro()
    {
        return $this->role === 'pro';
    }

    /**
     * Verifica si el usuario es administrador
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Calcula el promedio de calificación del profesional
     */
    public function averageRating()
    {
        return $this->reviewsReceived()->avg('rating') ?? 0;
    }
}
