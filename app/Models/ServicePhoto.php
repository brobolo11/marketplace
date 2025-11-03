<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePhoto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',   // ID del servicio al que pertenece la foto
        'image_path',   // Ruta de la imagen en el servidor
    ];

    // ========================================
    // RELACIONES DEL MODELO SERVICE_PHOTO
    // ========================================

    /**
     * Servicio al que pertenece esta foto.
     * Relación: Una foto pertenece a un servicio.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    /**
     * Obtiene la URL completa de la imagen.
     */
    public function getImageUrl(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
