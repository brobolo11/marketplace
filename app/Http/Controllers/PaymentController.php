<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Muestra el historial de pagos del usuario.
     * Si es cliente, muestra pagos realizados.
     * Si es profesional, muestra pagos recibidos.
     * 
     * GET /payments
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isPro()) {
            // Pagos recibidos como profesional
            $payments = $user->paymentsAsProfessional()
                ->with(['booking.service', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            // Estadísticas financieras
            $stats = [
                'total_earned' => $user->paymentsAsProfessional()->completed()->sum('professional_amount'),
                'pending_amount' => $user->paymentsAsProfessional()->pending()->sum('professional_amount'),
                'completed_count' => $user->paymentsAsProfessional()->completed()->count(),
                'platform_fees' => $user->paymentsAsProfessional()->completed()->sum('platform_fee'),
            ];
        } else {
            // Pagos realizados como cliente
            $payments = $user->paymentsAsClient()
                ->with(['booking.service', 'professional'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            // Estadísticas de gastos
            $stats = [
                'total_spent' => $user->paymentsAsClient()->completed()->sum('amount'),
                'pending_amount' => $user->paymentsAsClient()->pending()->sum('amount'),
                'completed_count' => $user->paymentsAsClient()->completed()->count(),
            ];
        }

        return view('payments.index', compact('payments', 'stats'));
    }

    /**
     * Muestra la página de checkout para una reserva.
     * 
     * GET /bookings/{booking}/checkout
     */
    public function checkout(Booking $booking)
    {
        $user = Auth::user();

        // Verifica que el usuario sea el cliente de la reserva
        if ($booking->user_id !== $user->id) {
            abort(403, 'No tienes permiso para pagar esta reserva.');
        }

        // Verifica que la reserva esté aceptada
        if (!$booking->isAccepted()) {
            return redirect()->route('bookings.show', $booking)
                ->withErrors(['error' => 'Solo puedes pagar reservas aceptadas.']);
        }

        // Verifica si ya existe un pago para esta reserva
        $existingPayment = $booking->payment;
        if ($existingPayment && $existingPayment->isCompleted()) {
            return redirect()->route('bookings.show', $booking)
                ->with('info', 'Esta reserva ya ha sido pagada.');
        }

        // Calcula las tarifas
        $amount = $booking->total_price;
        $platformFee = Payment::calculatePlatformFee($amount);
        $professionalAmount = Payment::calculateProfessionalAmount($amount, $platformFee);

        return view('payments.checkout', compact('booking', 'amount', 'platformFee', 'professionalAmount'));
    }

    /**
     * Procesa el pago simulado.
     * 
     * POST /payments
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:wallet,card,bank_transfer',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);
        $user = Auth::user();

        // Verifica permisos
        if ($booking->user_id !== $user->id) {
            abort(403, 'No tienes permiso para pagar esta reserva.');
        }

        // Verifica estado de la reserva
        if (!$booking->isAccepted()) {
            return back()->withErrors(['error' => 'Solo puedes pagar reservas aceptadas.']);
        }

        // Verifica si ya existe un pago completado
        $existingPayment = $booking->payment;
        if ($existingPayment && $existingPayment->isCompleted()) {
            return redirect()->route('bookings.show', $booking)
                ->with('info', 'Esta reserva ya ha sido pagada.');
        }

        try {
            DB::beginTransaction();

            // Calcula las tarifas
            $amount = $booking->total_price;
            $platformFee = Payment::calculatePlatformFee($amount);
            $professionalAmount = Payment::calculateProfessionalAmount($amount, $platformFee);

            // Simula proceso de pago (90% éxito, 10% fallo aleatorio)
            $paymentSuccess = rand(1, 100) <= 90;

            // Crea o actualiza el pago
            if ($existingPayment) {
                $payment = $existingPayment;
                $payment->update([
                    'payment_method' => $validated['payment_method'],
                    'transaction_id' => Payment::generateTransactionId(),
                    'status' => $paymentSuccess ? 'processing' : 'failed',
                ]);
            } else {
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'user_id' => $user->id,
                    'professional_id' => $booking->pro_id,
                    'amount' => $amount,
                    'platform_fee' => $platformFee,
                    'professional_amount' => $professionalAmount,
                    'payment_method' => $validated['payment_method'],
                    'transaction_id' => Payment::generateTransactionId(),
                    'status' => $paymentSuccess ? 'processing' : 'failed',
                ]);
            }

            // Simula tiempo de procesamiento (en producción esto sería asíncrono)
            if ($paymentSuccess) {
                sleep(1); // Simula delay de procesamiento
                $payment->markAsCompleted();
                
                // Actualiza el estado del booking a 'paid'
                $booking->status = 'paid';
                $booking->paid_at = now();
                $booking->save();
                
                // Notifica al profesional que recibió el pago
                NotificationService::paymentReceived($booking);
                
                DB::commit();

                return redirect()->route('payments.confirmation', $payment)
                    ->with('success', '¡Pago procesado exitosamente!');
            } else {
                $payment->markAsFailed('Pago rechazado por el banco simulado.');
                
                DB::commit();

                return redirect()->route('bookings.show', $booking)
                    ->withErrors(['error' => 'El pago fue rechazado. Por favor, intenta con otro método de pago.']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'Error al procesar el pago: ' . $e->getMessage()]);
        }
    }

    /**
     * Muestra la confirmación del pago.
     * 
     * GET /payments/{payment}/confirmation
     */
    public function confirmation(Payment $payment)
    {
        $user = Auth::user();

        // Verifica que el usuario tenga permiso para ver este pago
        if ($payment->user_id !== $user->id && $payment->professional_id !== $user->id) {
            abort(403, 'No tienes permiso para ver este pago.');
        }

        $payment->load(['booking.service', 'user', 'professional']);

        return view('payments.confirmation', compact('payment'));
    }

    /**
     * Solicita un reembolso (solo para pagos completados).
     * 
     * POST /payments/{payment}/refund
     */
    public function refund(Payment $payment)
    {
        $user = Auth::user();

        // Verifica que el usuario sea el cliente que pagó
        if ($payment->user_id !== $user->id) {
            abort(403, 'No tienes permiso para solicitar reembolso de este pago.');
        }

        // Verifica que el pago esté completado
        if (!$payment->isCompleted()) {
            return back()->withErrors(['error' => 'Solo puedes solicitar reembolso de pagos completados.']);
        }

        // Verifica que la reserva esté cancelada
        $booking = $payment->booking;
        if (!$booking->isCancelled()) {
            return back()->withErrors(['error' => 'Solo puedes solicitar reembolso de reservas canceladas.']);
        }

        try {
            // Procesa el reembolso (simulado)
            $payment->markAsRefunded();
            $payment->notes = 'Reembolso procesado por cancelación de reserva.';
            $payment->save();

            return back()->with('success', 'Reembolso procesado exitosamente. El dinero será devuelto en 3-5 días hábiles.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al procesar el reembolso: ' . $e->getMessage()]);
        }
    }

    /**
     * Descarga un recibo de pago en PDF (simulado).
     * 
     * GET /payments/{payment}/receipt
     */
    public function receipt(Payment $payment)
    {
        $user = Auth::user();

        // Verifica permisos
        if ($payment->user_id !== $user->id && $payment->professional_id !== $user->id) {
            abort(403, 'No tienes permiso para ver este recibo.');
        }

        $payment->load(['booking.service', 'user', 'professional']);

        // En producción, generarías un PDF real con DomPDF o similar
        return view('payments.receipt', compact('payment'));
    }
}
