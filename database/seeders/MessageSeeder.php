<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use Carbon\Carbon;

class MessageSeeder extends Seeder
{
    /**
     * Seed the messages table.
     * Crea conversaciones de ejemplo entre clientes y profesionales.
     */
    public function run(): void
    {
        // ========================================
        // CONVERSACIÓN 1: Roberto (cliente) ↔ Carlos (fontanero)
        // Sobre la reserva de reparación de fugas
        // ========================================

        Message::create([
            'sender_id' => 10,  // Roberto (cliente)
            'receiver_id' => 2, // Carlos (fontanero)
            'message' => 'Hola Carlos, tengo una fuga en el baño. ¿Podrías venir a verlo?',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(16),
        ]);

        Message::create([
            'sender_id' => 2,   // Carlos
            'receiver_id' => 10, // Roberto
            'message' => 'Hola Roberto, claro que sí. ¿Te vendría bien mañana por la mañana?',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(16)->addHours(2),
        ]);

        Message::create([
            'sender_id' => 10,  // Roberto
            'receiver_id' => 2, // Carlos
            'message' => 'Perfecto, ¿a las 10:00 te viene bien?',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(16)->addHours(3),
        ]);

        Message::create([
            'sender_id' => 2,   // Carlos
            'receiver_id' => 10, // Roberto
            'message' => '¡Perfecto! Nos vemos mañana a las 10:00. Llevo las herramientas necesarias.',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(16)->addHours(3)->addMinutes(15),
        ]);

        // ========================================
        // CONVERSACIÓN 2: Carmen (cliente) ↔ María (electricista)
        // Consulta sobre instalación domótica
        // ========================================

        Message::create([
            'sender_id' => 11,  // Carmen (cliente)
            'receiver_id' => 3, // María (electricista)
            'message' => 'Hola María, estoy interesada en instalar domótica en mi casa. ¿Cuánto costaría aproximadamente?',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(5),
        ]);

        Message::create([
            'sender_id' => 3,   // María
            'receiver_id' => 11, // Carmen
            'message' => 'Hola Carmen, depende de lo que quieras automatizar. Para una instalación básica estaríamos hablando de unas 8-10 horas de trabajo. Te puedo pasar presupuesto detallado si me dices qué necesitas.',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(5)->addHours(1),
        ]);

        Message::create([
            'sender_id' => 11,  // Carmen
            'receiver_id' => 3, // María
            'message' => 'Me gustaría automatizar las luces, persianas y el termostato. ¿Podrías venir a ver la casa?',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(4),
        ]);

        // ========================================
        // CONVERSACIÓN 3: David (cliente) ↔ Juan (jardinero)
        // Mensaje después del servicio
        // ========================================

        Message::create([
            'sender_id' => 12,  // David (cliente)
            'receiver_id' => 4, // Juan (jardinero)
            'message' => 'Hola Juan, muchas gracias por el trabajo de ayer. El jardín quedó perfecto.',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(6),
        ]);

        Message::create([
            'sender_id' => 4,   // Juan
            'receiver_id' => 12, // David
            'message' => 'Muchas gracias David! Me alegro que te haya gustado. Para el próximo mes te recomendaría hacer una poda de los setos.',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(6)->addHours(3),
        ]);

        // ========================================
        // CONVERSACIÓN 4: Elena (cliente) ↔ Laura (limpieza)
        // Sobre la próxima reserva
        // ========================================

        Message::create([
            'sender_id' => 13,  // Elena (cliente)
            'receiver_id' => 6, // Laura (limpieza)
            'message' => 'Hola Laura, confirmo la reserva para el martes. ¿Necesitas que esté en casa?',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(1),
        ]);

        Message::create([
            'sender_id' => 6,   // Laura
            'receiver_id' => 13, // Elena
            'message' => 'Hola Elena! No es necesario que estés, pero sí necesitaría las llaves. ¿Te parece bien si las recojo el lunes?',
            'is_read' => false, // Mensaje no leído
            'created_at' => Carbon::now()->subDays(1)->addHours(2),
        ]);

        Message::create([
            'sender_id' => 13,  // Elena
            'receiver_id' => 6, // Laura
            'message' => 'Perfecto, te las dejo en conserjería el lunes por la mañana.',
            'is_read' => false, // Mensaje no leído
            'created_at' => Carbon::now()->subHours(5),
        ]);

        // ========================================
        // CONVERSACIÓN 5: Francisco (cliente) ↔ Pedro (carpintero)
        // Consulta sobre muebles a medida
        // ========================================

        Message::create([
            'sender_id' => 14,  // Francisco (cliente)
            'receiver_id' => 7, // Pedro (carpintero)
            'message' => 'Hola Pedro, necesito un armario empotrado para mi dormitorio. ¿Haces presupuestos sin compromiso?',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        Message::create([
            'sender_id' => 7,   // Pedro
            'receiver_id' => 14, // Francisco
            'message' => 'Hola Francisco, sí, sin problema. ¿Me puedes pasar las medidas del hueco?',
            'is_read' => true,
            'created_at' => Carbon::now()->subDays(3)->addHours(4),
        ]);

        Message::create([
            'sender_id' => 14,  // Francisco
            'receiver_id' => 7, // Pedro
            'message' => 'El hueco mide 3 metros de ancho por 2.5 de alto. Necesitaría 3 puertas correderas.',
            'is_read' => false, // Mensaje no leído
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // ========================================
        // MENSAJES NO LEÍDOS RECIENTES
        // ========================================

        Message::create([
            'sender_id' => 11,  // Carmen
            'receiver_id' => 8, // Miguel (pintor)
            'message' => '¿Confirmas la reserva para la semana que viene?',
            'is_read' => false,
            'created_at' => Carbon::now()->subHours(2),
        ]);

        Message::create([
            'sender_id' => 12,  // David
            'receiver_id' => 9, // Sofía (mascotas)
            'message' => 'Hola Sofía, ¿estás disponible mañana para pasear a mi perro?',
            'is_read' => false,
            'created_at' => Carbon::now()->subMinutes(30),
        ]);
    }
}
