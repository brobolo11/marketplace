<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabla de mensajes para el chat entre clientes y profesionales
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Usuario que envía el mensaje
            $table->foreignId('sender_id')
                ->constrained('users')
                ->onDelete('cascade'); // Si se elimina el usuario, se eliminan sus mensajes enviados
            
            // Usuario que recibe el mensaje
            $table->foreignId('receiver_id')
                ->constrained('users')
                ->onDelete('cascade'); // Si se elimina el usuario, se eliminan sus mensajes recibidos
            
            // Contenido del mensaje
            $table->text('message');
            
            // Indica si el mensaje ha sido leído
            $table->boolean('is_read')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
