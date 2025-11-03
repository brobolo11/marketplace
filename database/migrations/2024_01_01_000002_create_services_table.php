<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de servicios ofrecidos por los profesionales
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            
            // ID del profesional que ofrece el servicio
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Si se elimina el usuario, se eliminan sus servicios
            
            // Categoría del servicio (fontanería, electricidad, etc.)
            $table->foreignId('category_id')
                ->constrained('categories')
                ->onDelete('cascade'); // Si se elimina la categoría, se eliminan los servicios asociados
            
            // Título descriptivo del servicio
            $table->string('title');
            
            // Descripción detallada del servicio ofrecido
            $table->text('description')->nullable();
            
            // Precio por hora del servicio (en euros, dólares, etc.)
            $table->decimal('price_hour', 8, 2); // Formato: 999999.99
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
