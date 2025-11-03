<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Seed the categories table.
     * Crea categorías de servicios comunes para el hogar.
     */
    public function run(): void
    {
        // Array de categorías con sus respectivos iconos
        $categories = [
            [
                'name' => 'Fontanería',
                'icon' => 'wrench',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Electricidad',
                'icon' => 'bolt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jardinería',
                'icon' => 'leaf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Limpieza',
                'icon' => 'broom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carpintería',
                'icon' => 'hammer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pintura',
                'icon' => 'paint-brush',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Clases Particulares',
                'icon' => 'book',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cuidado de Niños',
                'icon' => 'baby',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cuidado de Mascotas',
                'icon' => 'dog',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mudanzas',
                'icon' => 'truck',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Montaje de Muebles',
                'icon' => 'couch',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reparaciones del Hogar',
                'icon' => 'tools',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Inserta las categorías en la base de datos
        DB::table('categories')->insert($categories);
    }
}
