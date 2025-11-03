<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Seed the services table.
     * Crea servicios asociados a los profesionales.
     */
    public function run(): void
    {
        // ========================================
        // SERVICIOS DE FONTANERÍA
        // user_id: 2 (Carlos Rodríguez - Fontanero)
        // category_id: 1 (Fontanería)
        // ========================================
        
        Service::create([
            'user_id' => 2,
            'category_id' => 1,
            'title' => 'Reparación de fugas de agua',
            'description' => 'Detección y reparación de fugas en grifos, tuberías y cisternas. Servicio rápido y garantizado.',
            'price_hour' => 35.00,
        ]);

        Service::create([
            'user_id' => 2,
            'category_id' => 1,
            'title' => 'Instalación de sanitarios',
            'description' => 'Instalación completa de inodoros, lavabos, duchas y bañeras. Incluye materiales.',
            'price_hour' => 45.00,
        ]);

        // ========================================
        // SERVICIOS DE ELECTRICIDAD
        // user_id: 3 (María García - Electricista)
        // category_id: 2 (Electricidad)
        // ========================================

        Service::create([
            'user_id' => 3,
            'category_id' => 2,
            'title' => 'Instalación eléctrica completa',
            'description' => 'Instalación eléctrica para viviendas nuevas o reformas. Certificación oficial incluida.',
            'price_hour' => 50.00,
        ]);

        Service::create([
            'user_id' => 3,
            'category_id' => 2,
            'title' => 'Reparación de averías eléctricas',
            'description' => 'Diagnóstico y reparación de problemas eléctricos. Disponible 24/7 para emergencias.',
            'price_hour' => 40.00,
        ]);

        Service::create([
            'user_id' => 3,
            'category_id' => 2,
            'title' => 'Instalación de domótica',
            'description' => 'Automatización del hogar: luces, persianas, climatización. Sistema inteligente completo.',
            'price_hour' => 60.00,
        ]);

        // ========================================
        // SERVICIOS DE JARDINERÍA
        // user_id: 4 (Juan Martínez - Jardinero)
        // category_id: 3 (Jardinería)
        // ========================================

        Service::create([
            'user_id' => 4,
            'category_id' => 3,
            'title' => 'Mantenimiento de jardines',
            'description' => 'Corte de césped, poda de setos, riego y limpieza general del jardín.',
            'price_hour' => 25.00,
        ]);

        Service::create([
            'user_id' => 4,
            'category_id' => 3,
            'title' => 'Diseño y creación de jardines',
            'description' => 'Diseño personalizado de jardines, plantación de árboles y flores, sistema de riego.',
            'price_hour' => 35.00,
        ]);

        // ========================================
        // SERVICIOS DE CLASES PARTICULARES
        // user_id: 5 (Ana López - Profesora)
        // category_id: 7 (Clases Particulares)
        // ========================================

        Service::create([
            'user_id' => 5,
            'category_id' => 7,
            'title' => 'Clases de matemáticas ESO',
            'description' => 'Clases particulares de matemáticas para estudiantes de ESO. Refuerzo y preparación de exámenes.',
            'price_hour' => 20.00,
        ]);

        Service::create([
            'user_id' => 5,
            'category_id' => 7,
            'title' => 'Clases de física y química',
            'description' => 'Clases de física y química para Bachillerato y selectividad. Métodos didácticos probados.',
            'price_hour' => 25.00,
        ]);

        // ========================================
        // SERVICIOS DE LIMPIEZA
        // user_id: 6 (Laura Sánchez - Limpieza)
        // category_id: 4 (Limpieza)
        // ========================================

        Service::create([
            'user_id' => 6,
            'category_id' => 4,
            'title' => 'Limpieza general del hogar',
            'description' => 'Limpieza completa de viviendas: cocina, baños, habitaciones y zonas comunes. Productos ecológicos.',
            'price_hour' => 15.00,
        ]);

        Service::create([
            'user_id' => 6,
            'category_id' => 4,
            'title' => 'Limpieza profunda',
            'description' => 'Limpieza a fondo incluyendo cristales, electrodomésticos y zonas de difícil acceso.',
            'price_hour' => 20.00,
        ]);

        // ========================================
        // SERVICIOS DE CARPINTERÍA
        // user_id: 7 (Pedro Fernández - Carpintero)
        // category_id: 5 (Carpintería)
        // ========================================

        Service::create([
            'user_id' => 7,
            'category_id' => 5,
            'title' => 'Muebles a medida',
            'description' => 'Diseño y fabricación de muebles personalizados: armarios, estanterías, mesas.',
            'price_hour' => 40.00,
        ]);

        Service::create([
            'user_id' => 7,
            'category_id' => 5,
            'title' => 'Restauración de muebles',
            'description' => 'Restauración y reparación de muebles antiguos. Lijado, barnizado y acabados profesionales.',
            'price_hour' => 35.00,
        ]);

        // ========================================
        // SERVICIOS DE PINTURA
        // user_id: 8 (Miguel Torres - Pintor)
        // category_id: 6 (Pintura)
        // ========================================

        Service::create([
            'user_id' => 8,
            'category_id' => 6,
            'title' => 'Pintura de interiores',
            'description' => 'Pintura de paredes y techos de viviendas. Acabados perfectos garantizados.',
            'price_hour' => 18.00,
        ]);

        Service::create([
            'user_id' => 8,
            'category_id' => 6,
            'title' => 'Eliminación de gotelé',
            'description' => 'Eliminación profesional de gotelé y alisado de paredes. Acabado liso impecable.',
            'price_hour' => 22.00,
        ]);

        // ========================================
        // SERVICIOS DE CUIDADO DE MASCOTAS
        // user_id: 9 (Sofía Ramírez - Cuidadora)
        // category_id: 9 (Cuidado de Mascotas)
        // ========================================

        Service::create([
            'user_id' => 9,
            'category_id' => 9,
            'title' => 'Paseo de perros',
            'description' => 'Paseos diarios para tu perro. Atención personalizada y mucho cariño garantizado.',
            'price_hour' => 12.00,
        ]);

        Service::create([
            'user_id' => 9,
            'category_id' => 9,
            'title' => 'Cuidado de mascotas a domicilio',
            'description' => 'Cuidado de tus mascotas cuando estás de viaje. Comida, paseos y compañía.',
            'price_hour' => 15.00,
        ]);
    }
}
