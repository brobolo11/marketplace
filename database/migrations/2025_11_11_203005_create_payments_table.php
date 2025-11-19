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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cliente que paga
            $table->foreignId('professional_id')->constrained('users')->onDelete('cascade'); // Profesional que recibe
            $table->decimal('amount', 10, 2); // Monto total
            $table->decimal('platform_fee', 10, 2)->default(0); // Comisión de plataforma (ej: 10%)
            $table->decimal('professional_amount', 10, 2); // Lo que recibe el profesional
            $table->string('payment_method')->default('wallet'); // wallet, card, bank_transfer
            $table->string('transaction_id')->unique()->nullable(); // ID de transacción simulado
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->text('notes')->nullable(); // Notas adicionales
            $table->timestamp('paid_at')->nullable(); // Fecha de pago completado
            $table->timestamps();
            
            // Índices para búsquedas rápidas
            $table->index(['user_id', 'status']);
            $table->index(['professional_id', 'status']);
            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
