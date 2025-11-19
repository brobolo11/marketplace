<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agregar campos para bloqueos específicos y disponibilidad
     */
    public function up(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            // Fecha específica para bloqueos (vacaciones, días festivos, etc.)
            $table->date('specific_date')->nullable()->after('weekday');
            
            // Indica si está disponible o bloqueado (true = disponible, false = bloqueado)
            $table->boolean('is_available')->default(true)->after('end_time');
            
            // Razón del bloqueo (opcional)
            $table->string('reason')->nullable()->after('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn(['specific_date', 'is_available', 'reason']);
        });
    }
};
