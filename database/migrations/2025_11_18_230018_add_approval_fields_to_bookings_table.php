<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Campos para gestión de aprobación/rechazo
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
            $table->timestamp('completed_at')->nullable()->after('rejection_reason');
            
            // Campo para descripción del cliente
            $table->text('description')->nullable()->after('address');
            
            // Campo para notas del profesional
            $table->text('professional_notes')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'approved_at',
                'rejected_at',
                'rejection_reason',
                'completed_at',
                'description',
                'professional_notes'
            ]);
        });
    }
};
