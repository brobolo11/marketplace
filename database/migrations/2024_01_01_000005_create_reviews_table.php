<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de reseñas y valoraciones de servicios completados
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // Reserva asociada a esta reseña
            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->onDelete('cascade'); // Si se elimina la reserva, se elimina la reseña
            
            // Cliente que escribe la reseña
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Si se elimina el cliente, se eliminan sus reseñas
            
            // Profesional que recibe la reseña
            $table->foreignId('pro_id')
                ->constrained('users')
                ->onDelete('cascade'); // Si se elimina el profesional, se eliminan sus reseñas
            
            // Puntuación del servicio (1 a 5 estrellas)
            $table->unsignedTinyInteger('rating'); // Valores de 1 a 5
            
            // Comentario opcional del cliente sobre el servicio recibido
            $table->text('comment')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
