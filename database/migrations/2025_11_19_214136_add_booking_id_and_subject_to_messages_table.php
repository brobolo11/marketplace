<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agregar campos opcionales para contexto de mensajes
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Referencia opcional a una reserva (contexto del mensaje)
            $table->foreignId('booking_id')->nullable()->after('receiver_id')
                ->constrained('bookings')
                ->onDelete('set null');
            
            // Asunto del mensaje (opcional)
            $table->string('subject')->nullable()->after('booking_id');
            
            // Cambiar nombre de columna 'is_read' a 'read_at' (timestamp)
            $table->timestamp('read_at')->nullable()->after('message');
        });
        
        // Migrar datos existentes
        DB::statement('UPDATE messages SET read_at = updated_at WHERE is_read = true');
        
        // Eliminar columna antigua
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('message');
            $table->dropForeign(['booking_id']);
            $table->dropColumn(['booking_id', 'subject', 'read_at']);
        });
    }
};
