<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table.
     * Crea usuarios de prueba: admin, profesionales y clientes.
     */
    public function run(): void
    {
        // ========================================
        // USUARIO ADMINISTRADOR
        // ========================================
        
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@servicios.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+34 600 000 000',
            'city' => 'Madrid',
            'lat' => 40.416775,
            'lng' => -3.703790,
            'bio' => 'Administrador del sistema',
        ]);

        // ========================================
        // PROFESIONALES
        // ========================================

        // Fontanero
        User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'carlos@profesionales.com',
            'password' => Hash::make('password'),
            'role' => 'pro',
            'phone' => '+34 611 111 111',
            'city' => 'Madrid',
            'lat' => 40.416775,
            'lng' => -3.703790,
            'bio' => 'Fontanero profesional con más de 10 años de experiencia. Especializado en reparaciones de urgencia y instalaciones completas.',
            'profile_photo_path' => 'https://ui-avatars.com/api/?name=Carlos+Rodriguez&background=4F46E5&color=fff&size=200',
        ]);

        // Electricista
        User::create([
            'name' => 'María García',
            'email' => 'maria@profesionales.com',
            'password' => Hash::make('password'),
            'role' => 'pro',
            'phone' => '+34 622 222 222',
            'city' => 'Barcelona',
            'lat' => 41.385064,
            'lng' => 2.173404,
            'bio' => 'Electricista certificada. Instalaciones eléctricas, domótica y energía solar. Disponible para emergencias 24/7.',
            'profile_photo_path' => 'https://ui-avatars.com/api/?name=Maria+Garcia&background=9333EA&color=fff&size=200',
        ]);

        // Jardinero
        User::create([
            'name' => 'Juan Martínez',
            'email' => 'juan@profesionales.com',
            'password' => Hash::make('password'),
            'role' => 'pro',
            'phone' => '+34 633 333 333',
            'city' => 'Valencia',
            'lat' => 39.469907,
            'lng' => -0.376288,
            'bio' => 'Jardinero profesional. Diseño de jardines, mantenimiento y poda de árboles. Servicio de jardinería integral.',
            'profile_photo_path' => 'https://ui-avatars.com/api/?name=Juan+Martinez&background=10B981&color=fff&size=200',
        ]);

        // Profesor particular
        User::create([
            'name' => 'Ana López',
            'email' => 'ana@profesionales.com',
            'password' => Hash::make('password'),
            'role' => 'pro',
            'phone' => '+34 644 444 444',
            'city' => 'Madrid',
            'lat' => 40.420000,
            'lng' => -3.700000,
            'bio' => 'Profesora de matemáticas y física con 8 años de experiencia. Clases personalizadas para ESO y Bachillerato.',
            'profile_photo_path' => 'https://ui-avatars.com/api/?name=Ana+Lopez&background=F59E0B&color=fff&size=200',
        ]);

        // Limpieza
        User::create([
            'name' => 'Laura Sánchez',
            'email' => 'laura@profesionales.com',
            'password' => Hash::make('password'),
            'role' => 'pro',
            'phone' => '+34 655 555 555',
            'city' => 'Sevilla',
            'lat' => 37.388096,
            'lng' => -5.982330,
            'bio' => 'Servicio de limpieza profesional para hogares y oficinas. Productos ecológicos y atención personalizada.',
            'profile_photo_path' => 'https://ui-avatars.com/api/?name=Laura+Sanchez&background=EC4899&color=fff&size=200',
        ]);

        // Carpintero
        User::create([
            'name' => 'Pedro Fernández',
            'email' => 'pedro@profesionales.com',
            'password' => Hash::make('password'),
            'role' => 'pro',
            'phone' => '+34 666 666 666',
            'city' => 'Bilbao',
            'lat' => 43.263012,
            'lng' => -2.934985,
            'bio' => 'Carpintero especializado en muebles a medida y reformas de interiores. Más de 15 años de experiencia.',
            'profile_photo_path' => 'https://ui-avatars.com/api/?name=Pedro+Fernandez&background=0891B2&color=fff&size=200',
        ]);

        // Pintor
        User::create([
            'name' => 'Miguel Torres',
            'email' => 'miguel@profesionales.com',
            'password' => Hash::make('password'),
            'role' => 'pro',
            'phone' => '+34 677 777 777',
            'city' => 'Madrid',
            'lat' => 40.425000,
            'lng' => -3.695000,
            'bio' => 'Pintor profesional con experiencia en pintura decorativa, gotelé y trabajos en altura.',
            'profile_photo_path' => 'https://ui-avatars.com/api/?name=Miguel+Torres&background=DC2626&color=fff&size=200',
        ]);

        // Cuidador de mascotas
        User::create([
            'name' => 'Sofía Ramírez',
            'email' => 'sofia@profesionales.com',
            'password' => Hash::make('password'),
            'role' => 'pro',
            'phone' => '+34 688 888 888',
            'city' => 'Barcelona',
            'lat' => 41.390000,
            'lng' => 2.170000,
            'bio' => 'Cuidadora profesional de mascotas. Paseos, cuidados veterinarios y hospedaje. Amante de los animales.',
            'profile_photo_path' => 'https://ui-avatars.com/api/?name=Sofia+Ramirez&background=8B5CF6&color=fff&size=200',
        ]);

        // ========================================
        // CLIENTES
        // ========================================

        // Cliente 1
        User::create([
            'name' => 'Roberto Díaz',
            'email' => 'roberto@clientes.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '+34 699 999 999',
            'city' => 'Madrid',
            'lat' => 40.430000,
            'lng' => -3.690000,
            'bio' => null,
        ]);

        // Cliente 2
        User::create([
            'name' => 'Carmen Ruiz',
            'email' => 'carmen@clientes.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '+34 610 101 010',
            'city' => 'Barcelona',
            'lat' => 41.395000,
            'lng' => 2.165000,
            'bio' => null,
        ]);

        // Cliente 3
        User::create([
            'name' => 'David Moreno',
            'email' => 'david@clientes.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '+34 621 212 121',
            'city' => 'Valencia',
            'lat' => 39.475000,
            'lng' => -0.380000,
            'bio' => null,
        ]);

        // Cliente 4
        User::create([
            'name' => 'Elena Jiménez',
            'email' => 'elena@clientes.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '+34 632 323 232',
            'city' => 'Sevilla',
            'lat' => 37.390000,
            'lng' => -5.985000,
            'bio' => null,
        ]);

        // Cliente 5
        User::create([
            'name' => 'Francisco Navarro',
            'email' => 'francisco@clientes.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '+34 643 434 343',
            'city' => 'Bilbao',
            'lat' => 43.265000,
            'lng' => -2.930000,
            'bio' => null,
        ]);
    }
}
