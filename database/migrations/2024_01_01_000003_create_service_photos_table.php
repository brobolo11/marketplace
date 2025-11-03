<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de fotos/imágenes de los servicios (portafolio del profesional)
     */
    public function up(): void
    {
        Schema::create('service_photos', function (Blueprint $table) {
            $table->id();
            
            // Servicio al que pertenece la foto
            $table->foreignId('service_id')
                ->constrained('services')
                ->onDelete('cascade'); // Si se elimina el servicio, se eliminan sus fotos
            
            // Ruta donde se almacena la imagen en el servidor
            $table->string('image_path');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_photos');
    }
};
