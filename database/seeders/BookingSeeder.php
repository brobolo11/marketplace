<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Seed the bookings table.
     * Crea reservas de ejemplo entre clientes y profesionales.
     */
    public function run(): void
    {
        // ========================================
        // RESERVAS COMPLETADAS (para poder tener reseñas)
        // ========================================

        // Cliente 1 (Roberto) contrató al fontanero (Carlos) - COMPLETADA
        Booking::create([
            'user_id' => 10,  // Roberto (cliente)
            'pro_id' => 2,    // Carlos (fontanero)
            'service_id' => 1,  // Reparación de fugas
            'datetime' => Carbon::now()->subDays(15)->setHour(10)->setMinute(0),
            'address' => 'Calle Mayor 15, Madrid',
            'status' => 'completed',
            'total_price' => 105.00, // 3 horas
        ]);

        // Cliente 2 (Carmen) contrató a la electricista (María) - COMPLETADA
        Booking::create([
            'user_id' => 11,  // Carmen (cliente)
            'pro_id' => 3,    // María (electricista)
            'service_id' => 4,  // Reparación de averías
            'datetime' => Carbon::now()->subDays(10)->setHour(14)->setMinute(0),
            'address' => 'Avinguda Diagonal 200, Barcelona',
            'status' => 'completed',
            'total_price' => 80.00, // 2 horas
        ]);

        // Cliente 3 (David) contrató al jardinero (Juan) - COMPLETADA
        Booking::create([
            'user_id' => 12,  // David (cliente)
            'pro_id' => 4,    // Juan (jardinero)
            'service_id' => 6,  // Mantenimiento de jardines
            'datetime' => Carbon::now()->subDays(7)->setHour(9)->setMinute(0),
            'address' => 'Calle Colón 45, Valencia',
            'status' => 'completed',
            'total_price' => 75.00, // 3 horas
        ]);

        // Cliente 1 (Roberto) contrató a la profesora (Ana) - COMPLETADA
        Booking::create([
            'user_id' => 10,  // Roberto (cliente)
            'pro_id' => 5,    // Ana (profesora)
            'service_id' => 8,  // Clases de matemáticas
            'datetime' => Carbon::now()->subDays(5)->setHour(17)->setMinute(0),
            'address' => 'Calle Serrano 80, Madrid',
            'status' => 'completed',
            'total_price' => 40.00, // 2 horas
        ]);

        // ========================================
        // RESERVAS ACEPTADAS (próximas)
        // ========================================

        // Cliente 4 (Elena) contrató servicio de limpieza (Laura) - ACEPTADA
        Booking::create([
            'user_id' => 13,  // Elena (cliente)
            'pro_id' => 6,    // Laura (limpieza)
            'service_id' => 10,  // Limpieza general
            'datetime' => Carbon::now()->addDays(2)->setHour(10)->setMinute(0),
            'address' => 'Calle Sierpes 30, Sevilla',
            'status' => 'accepted',
            'total_price' => 60.00, // 4 horas
        ]);

        // Cliente 5 (Francisco) contrató al carpintero (Pedro) - ACEPTADA
        Booking::create([
            'user_id' => 14,  // Francisco (cliente)
            'pro_id' => 7,    // Pedro (carpintero)
            'service_id' => 12,  // Muebles a medida
            'datetime' => Carbon::now()->addDays(5)->setHour(11)->setMinute(0),
            'address' => 'Gran Vía 25, Bilbao',
            'status' => 'accepted',
            'total_price' => 200.00, // 5 horas
        ]);

        // ========================================
        // RESERVAS PENDIENTES (esperando confirmación)
        // ========================================

        // Cliente 2 (Carmen) contrató al pintor (Miguel) - PENDIENTE
        Booking::create([
            'user_id' => 11,  // Carmen (cliente)
            'pro_id' => 8,    // Miguel (pintor)
            'service_id' => 14,  // Pintura de interiores
            'datetime' => Carbon::now()->addDays(7)->setHour(9)->setMinute(0),
            'address' => 'Passeig de Gràcia 50, Barcelona',
            'status' => 'pending',
            'total_price' => 144.00, // 8 horas
        ]);

        // Cliente 3 (David) contrató cuidado de mascotas (Sofía) - PENDIENTE
        Booking::create([
            'user_id' => 12,  // David (cliente)
            'pro_id' => 9,    // Sofía (mascotas)
            'service_id' => 17,  // Paseo de perros
            'datetime' => Carbon::now()->addDays(3)->setHour(18)->setMinute(0),
            'address' => 'Calle de la Paz 12, Valencia',
            'status' => 'pending',
            'total_price' => 12.00, // 1 hora
        ]);

        // ========================================
        // RESERVAS RECHAZADAS
        // ========================================

        // Cliente 4 (Elena) intentó contratar al fontanero pero fue rechazada
        Booking::create([
            'user_id' => 13,  // Elena (cliente)
            'pro_id' => 2,    // Carlos (fontanero)
            'service_id' => 2,  // Instalación de sanitarios
            'datetime' => Carbon::now()->addDays(1)->setHour(15)->setMinute(0),
            'address' => 'Calle Betis 40, Sevilla',
            'status' => 'rejected',
            'total_price' => 180.00,
        ]);

        // ========================================
        // RESERVAS CANCELADAS (por el cliente)
        // ========================================

        // Cliente 5 (Francisco) canceló su reserva con la electricista
        Booking::create([
            'user_id' => 14,  // Francisco (cliente)
            'pro_id' => 3,    // María (electricista)
            'service_id' => 3,  // Instalación eléctrica
            'datetime' => Carbon::now()->addDays(4)->setHour(10)->setMinute(0),
            'address' => 'Plaza Moyúa 8, Bilbao',
            'status' => 'cancelled',
            'total_price' => 250.00,
        ]);
    }
}
