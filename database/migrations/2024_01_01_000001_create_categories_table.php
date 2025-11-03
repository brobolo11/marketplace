<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de categorías de servicios (fontanero, electricista, jardinero, etc.)
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            
            // Nombre de la categoría (ej: "Fontanería", "Electricidad", "Jardinería")
            $table->string('name');
            
            // Icono o imagen representativa de la categoría (opcional)
            $table->string('icon')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
