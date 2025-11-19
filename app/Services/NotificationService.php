<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Booking;

class NotificationService
{
    /**
     * Crea una notificación de solicitud de reserva (para profesionales).
     */
    public static function bookingRequest(Booking $booking): Notification
    {
        return Notification::create([
            'user_id' => $booking->pro_id,
            'type' => 'booking_request',
            'title' => 'Nueva Solicitud de Reserva',
            'message' => "{$booking->client->name} ha solicitado reservar tu servicio '{$booking->service->title}'.",
            'link' => route('bookings.pendingRequests'),
        ]);
    }

    /**
     * Crea una notificación de reserva aprobada (para clientes).
     */
    public static function bookingAccepted(Booking $booking): Notification
    {
        return Notification::create([
            'user_id' => $booking->user_id,
            'type' => 'booking_accepted',
            'title' => '¡Reserva Aprobada!',
            'message' => "Tu reserva para '{$booking->service->title}' ha sido aprobada. Por favor, procede con el pago.",
            'link' => route('bookings.checkout', $booking->id),
        ]);
    }

    /**
     * Crea una notificación de reserva rechazada (para clientes).
     */
    public static function bookingRejected(Booking $booking, string $reason): Notification
    {
        return Notification::create([
            'user_id' => $booking->user_id,
            'type' => 'booking_rejected',
            'title' => 'Reserva Rechazada',
            'message' => "Tu reserva para '{$booking->service->title}' ha sido rechazada. Motivo: {$reason}",
            'link' => route('bookings.show', $booking->id),
        ]);
    }

    /**
     * Crea una notificación de reserva cancelada (para profesionales).
     */
    public static function bookingCancelled(Booking $booking): Notification
    {
        return Notification::create([
            'user_id' => $booking->pro_id,
            'type' => 'booking_cancelled',
            'title' => 'Reserva Cancelada',
            'message' => "{$booking->client->name} ha cancelado su reserva para '{$booking->service->title}'.",
            'link' => route('bookings.show', $booking->id),
        ]);
    }

    /**
     * Crea una notificación de reserva completada (para clientes).
     */
    public static function bookingCompleted(Booking $booking): Notification
    {
        return Notification::create([
            'user_id' => $booking->user_id,
            'type' => 'booking_completed',
            'title' => 'Servicio Completado',
            'message' => "El servicio '{$booking->service->title}' ha sido completado. ¿Quieres dejar una reseña?",
            'link' => route('bookings.show', $booking->id),
        ]);
    }

    /**
     * Crea una notificación de pago recibido (para profesionales).
     */
    public static function paymentReceived(Booking $booking): Notification
    {
        return Notification::create([
            'user_id' => $booking->pro_id,
            'type' => 'payment_received',
            'title' => 'Pago Recibido',
            'message' => "Has recibido el pago de ${$booking->total_price} por la reserva de {$booking->client->name}.",
            'link' => route('bookings.show', $booking->id),
        ]);
    }

    /**
     * Crea una notificación de nueva reseña (para profesionales).
     */
    public static function newReview(Booking $booking, int $rating): Notification
    {
        $stars = str_repeat('⭐', $rating);
        
        return Notification::create([
            'user_id' => $booking->pro_id,
            'type' => 'new_review',
            'title' => 'Nueva Reseña Recibida',
            'message' => "{$booking->client->name} ha dejado una reseña de {$stars} para tu servicio '{$booking->service->title}'.",
            'link' => route('services.show', $booking->service_id),
        ]);
    }

    /**
     * Crea una notificación de mensaje nuevo (para destinatario).
     */
    public static function newMessage(int $recipientId, string $senderName, int $messageId): Notification
    {
        return Notification::create([
            'user_id' => $recipientId,
            'type' => 'new_message',
            'title' => 'Nuevo Mensaje',
            'message' => "{$senderName} te ha enviado un mensaje.",
            'link' => route('messages.show', $messageId),
        ]);
    }
}
