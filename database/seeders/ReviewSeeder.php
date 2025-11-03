<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Seed the reviews table.
     * Crea reseñas para las reservas completadas.
     */
    public function run(): void
    {
        // ========================================
        // RESEÑAS PARA RESERVAS COMPLETADAS
        // Solo se pueden hacer reseñas de bookings con status 'completed'
        // ========================================

        // Reseña para el fontanero (Carlos) por la reserva #1
        Review::create([
            'booking_id' => 1,  // Reserva completada
            'user_id' => 10,    // Roberto (cliente que deja la reseña)
            'pro_id' => 2,      // Carlos (fontanero que recibe la reseña)
            'rating' => 5,      // 5 estrellas
            'comment' => 'Excelente profesional. Llegó puntual, resolvió el problema rápidamente y dejó todo limpio. Muy recomendable.',
        ]);

        // Reseña para la electricista (María) por la reserva #2
        Review::create([
            'booking_id' => 2,  // Reserva completada
            'user_id' => 11,    // Carmen (cliente)
            'pro_id' => 3,      // María (electricista)
            'rating' => 5,      // 5 estrellas
            'comment' => 'Servicio impecable. María es muy profesional y solucionó el problema eléctrico de forma eficiente. ¡Totalmente recomendable!',
        ]);

        // Reseña para el jardinero (Juan) por la reserva #3
        Review::create([
            'booking_id' => 3,  // Reserva completada
            'user_id' => 12,    // David (cliente)
            'pro_id' => 4,      // Juan (jardinero)
            'rating' => 4,      // 4 estrellas
            'comment' => 'Buen trabajo en general. El jardín quedó muy bien cuidado. Le daría 5 estrellas si hubiera sido un poco más puntual.',
        ]);

        // Reseña para la profesora (Ana) por la reserva #4
        Review::create([
            'booking_id' => 4,  // Reserva completada
            'user_id' => 10,    // Roberto (cliente)
            'pro_id' => 5,      // Ana (profesora)
            'rating' => 5,      // 5 estrellas
            'comment' => 'Excelente profesora. Mi hijo ha mejorado mucho en matemáticas gracias a sus clases. Muy paciente y con métodos efectivos.',
        ]);

        // ========================================
        // RESEÑAS ADICIONALES (simulando más historial)
        // Creamos reseñas adicionales para algunos profesionales
        // Nota: Estas reseñas tendrían que estar asociadas a bookings,
        // pero para el ejemplo las dejamos sin booking_id real
        // En producción SIEMPRE deberían estar asociadas a una reserva
        // ========================================

        // Más reseñas para el fontanero (Carlos)
        Review::create([
            'booking_id' => 1,  // Reutilizamos booking para ejemplo
            'user_id' => 11,    // Carmen
            'pro_id' => 2,      // Carlos
            'rating' => 4,
            'comment' => 'Buen servicio, solucionó la fuga rápidamente. Precio justo.',
        ]);

        // Más reseñas para la electricista (María)
        Review::create([
            'booking_id' => 2,  // Reutilizamos booking
            'user_id' => 12,    // David
            'pro_id' => 3,      // María
            'rating' => 5,
            'comment' => 'Profesional de 10. Resolvió una avería compleja sin problemas. Muy satisfecho con el servicio.',
        ]);

        // Reseña para el pintor (aunque no tiene bookings completados aún)
        // En un caso real, esto no debería permitirse
        Review::create([
            'booking_id' => 7,  // Booking pendiente (en realidad no debería poder reseñar)
            'user_id' => 13,    // Elena
            'pro_id' => 8,      // Miguel (pintor)
            'rating' => 3,
            'comment' => 'Trabajo correcto pero le faltó más atención a los detalles. Precio razonable.',
        ]);

        // Reseña para la limpieza
        Review::create([
            'booking_id' => 5,  // Booking aceptado (no completado aún)
            'user_id' => 10,    // Roberto
            'pro_id' => 6,      // Laura (limpieza)
            'rating' => 5,
            'comment' => 'Increíble servicio de limpieza. Dejó mi casa impecable. Repetiré sin duda.',
        ]);
    }
}
