<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Booking;

class PaymentSeeder extends Seeder
{
    /**
     * Genera pagos simulados para las reservas existentes.
     */
    public function run(): void
    {
        $this->command->info('Generando pagos simulados...');

        // Obtiene solo las reservas completadas (las aceptadas aún no se han pagado)
        $bookings = Booking::where('status', 'completed')->get();

        foreach ($bookings as $booking) {
            // Calcula las tarifas
            $amount = $booking->total_price;
            $platformFee = Payment::calculatePlatformFee($amount);
            $professionalAmount = Payment::calculateProfessionalAmount($amount, $platformFee);

            // Todas las reservas aquí están completadas, por lo tanto el pago también
            $paymentStatus = 'completed';
            $paidAt = $booking->updated_at;

            // Crea el pago
            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'professional_id' => $booking->pro_id,
                'amount' => $amount,
                'platform_fee' => $platformFee,
                'professional_amount' => $professionalAmount,
                'payment_method' => $this->randomPaymentMethod(),
                'transaction_id' => Payment::generateTransactionId(),
                'status' => $paymentStatus,
                'paid_at' => $paidAt,
                'created_at' => $booking->created_at,
                'updated_at' => $booking->updated_at,
            ]);
        }

        $this->command->info('✅ Pagos simulados creados: ' . $bookings->count());
        
        // Estadísticas
        $this->command->info('');
        $this->command->info('Estadísticas de pagos:');
        $this->command->info('  - Completados: ' . Payment::where('status', 'completed')->count());
        $this->command->info('  - Pendientes: ' . Payment::where('status', 'pending')->count());
        $this->command->info('  - Total recaudado: ' . number_format(Payment::where('status', 'completed')->sum('amount'), 2) . '€');
        $this->command->info('  - Comisiones plataforma: ' . number_format(Payment::where('status', 'completed')->sum('platform_fee'), 2) . '€');
    }

    /**
     * Retorna un método de pago aleatorio.
     */
    private function randomPaymentMethod(): string
    {
        $methods = ['card', 'wallet', 'bank_transfer'];
        return $methods[array_rand($methods)];
    }
}
