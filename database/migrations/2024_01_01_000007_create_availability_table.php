<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de disponibilidad horaria de los profesionales
     */
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            
            // Profesional al que pertenece esta disponibilidad
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Si se elimina el profesional, se elimina su disponibilidad
            
            // Día de la semana (0 = Domingo, 1 = Lunes, ..., 6 = Sábado)
            $table->unsignedTinyInteger('weekday'); // Valores de 0 a 6
            
            // Hora de inicio de disponibilidad (ej: 09:00)
            $table->time('start_time');
            
            // Hora de fin de disponibilidad (ej: 18:00)
            $table->time('end_time');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
