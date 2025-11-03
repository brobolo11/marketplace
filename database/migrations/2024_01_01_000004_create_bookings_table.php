<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de reservas/citas entre clientes y profesionales
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // Cliente que realiza la reserva
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Si se elimina el cliente, se eliminan sus reservas
            
            // Profesional contratado
            $table->foreignId('pro_id')
                ->constrained('users')
                ->onDelete('cascade'); // Si se elimina el profesional, se eliminan sus reservas
            
            // Servicio reservado
            $table->foreignId('service_id')
                ->constrained('services')
                ->onDelete('cascade'); // Si se elimina el servicio, se eliminan las reservas
            
            // Fecha y hora de la cita
            $table->dateTime('datetime');
            
            // Dirección donde se realizará el servicio
            $table->string('address');
            
            // Estado de la reserva: pending (pendiente), accepted (aceptada), rejected (rechazada), 
            // completed (completada), cancelled (cancelada)
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed', 'cancelled'])
                ->default('pending');
            
            // Precio total acordado para el servicio
            $table->decimal('total_price', 8, 2); // Formato: 999999.99
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
