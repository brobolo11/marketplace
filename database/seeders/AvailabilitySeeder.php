<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Availability;

class AvailabilitySeeder extends Seeder
{
    /**
     * Seed the availability table.
     * Crea disponibilidad horaria para los profesionales.
     */
    public function run(): void
    {
        // ========================================
        // DISPONIBILIDAD PARA CADA PROFESIONAL
        // Los profesionales son los usuarios del 2 al 9
        // ========================================

        // Horario estándar de lunes a viernes: 9:00 - 18:00
        $standardSchedule = [
            ['weekday' => 1, 'start_time' => '09:00', 'end_time' => '18:00'], // Lunes
            ['weekday' => 2, 'start_time' => '09:00', 'end_time' => '18:00'], // Martes
            ['weekday' => 3, 'start_time' => '09:00', 'end_time' => '18:00'], // Miércoles
            ['weekday' => 4, 'start_time' => '09:00', 'end_time' => '18:00'], // Jueves
            ['weekday' => 5, 'start_time' => '09:00', 'end_time' => '18:00'], // Viernes
        ];

        // Horario con sábados: 9:00 - 14:00
        $weekendSchedule = [
            ['weekday' => 6, 'start_time' => '10:00', 'end_time' => '14:00'], // Sábado
        ];

        // ========================================
        // Carlos Rodríguez - Fontanero (user_id: 2)
        // Disponible lunes a viernes + sábados por la mañana
        // ========================================
        
        foreach ($standardSchedule as $schedule) {
            Availability::create(array_merge(['user_id' => 2], $schedule));
        }
        foreach ($weekendSchedule as $schedule) {
            Availability::create(array_merge(['user_id' => 2], $schedule));
        }

        // ========================================
        // María García - Electricista (user_id: 3)
        // Disponible 24/7 (simulamos con horario amplio)
        // ========================================
        
        for ($day = 1; $day <= 7; $day++) {
            Availability::create([
                'user_id' => 3,
                'weekday' => $day % 7, // 0 = Domingo, 1-6 = Lunes a Sábado
                'start_time' => '08:00',
                'end_time' => '22:00',
            ]);
        }

        // ========================================
        // Juan Martínez - Jardinero (user_id: 4)
        // Disponible lunes a sábado
        // ========================================
        
        foreach ($standardSchedule as $schedule) {
            Availability::create(array_merge(['user_id' => 4], $schedule));
        }
        Availability::create([
            'user_id' => 4,
            'weekday' => 6,
            'start_time' => '08:00',
            'end_time' => '15:00',
        ]);

        // ========================================
        // Ana López - Profesora (user_id: 5)
        // Tardes de lunes a viernes (horario escolar)
        // ========================================
        
        for ($day = 1; $day <= 5; $day++) {
            Availability::create([
                'user_id' => 5,
                'weekday' => $day,
                'start_time' => '16:00',
                'end_time' => '21:00',
            ]);
        }

        // ========================================
        // Laura Sánchez - Limpieza (user_id: 6)
        // Disponible mañanas de lunes a sábado
        // ========================================
        
        for ($day = 1; $day <= 6; $day++) {
            Availability::create([
                'user_id' => 6,
                'weekday' => $day,
                'start_time' => '08:00',
                'end_time' => '14:00',
            ]);
        }

        // ========================================
        // Pedro Fernández - Carpintero (user_id: 7)
        // Disponible lunes a viernes
        // ========================================
        
        foreach ($standardSchedule as $schedule) {
            Availability::create(array_merge(['user_id' => 7], $schedule));
        }

        // ========================================
        // Miguel Torres - Pintor (user_id: 8)
        // Disponible lunes a viernes + sábados
        // ========================================
        
        foreach ($standardSchedule as $schedule) {
            Availability::create(array_merge(['user_id' => 8], $schedule));
        }
        foreach ($weekendSchedule as $schedule) {
            Availability::create(array_merge(['user_id' => 8], $schedule));
        }

        // ========================================
        // Sofía Ramírez - Cuidadora de mascotas (user_id: 9)
        // Disponible todos los días con horarios flexibles
        // ========================================
        
        for ($day = 0; $day <= 6; $day++) {
            Availability::create([
                'user_id' => 9,
                'weekday' => $day,
                'start_time' => '07:00',
                'end_time' => '20:00',
            ]);
        }
    }
}
