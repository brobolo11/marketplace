<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder principal de la base de datos
 * Ejecuta todos los seeders en el orden correcto para poblar la base de datos
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders de la aplicación
     * 
     * ORDEN DE EJECUCIÓN:
     * 1. Categorías (independiente)
     * 2. Usuarios (independiente)
     * 3. Servicios (depende de categorías y usuarios)
     * 4. Disponibilidad (depende de usuarios)
     * 5. Reservas (depende de servicios, usuarios)
     * 6. Reseñas (depende de reservas)
     * 7. Mensajes (depende de usuarios)
     */
    public function run(): void
    {
        // Orden de ejecución de seeders
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            ServiceSeeder::class,
            AvailabilitySeeder::class,
            BookingSeeder::class,
            PaymentSeeder::class,  // Añadido: genera pagos para reservas existentes
            ReviewSeeder::class,
            MessageSeeder::class,
        ]);

        // Mostrar resumen de datos creados
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('  DATOS DE PRUEBA CREADOS EXITOSAMENTE');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Credenciales de acceso:');
        $this->command->info('');
        $this->command->warn('ADMINISTRADOR:');
        $this->command->line('  Email: admin@servicios.com');
        $this->command->line('  Password: password');
        $this->command->info('');
        $this->command->warn('PROFESIONALES:');
        $this->command->line('  carlos@profesionales.com');
        $this->command->line('  maria@profesionales.com');
        $this->command->line('  juan@profesionales.com');
        $this->command->line('  ana@profesionales.com');
        $this->command->line('  laura@profesionales.com');
        $this->command->line('  pedro@profesionales.com');
        $this->command->line('  miguel@profesionales.com');
        $this->command->line('  sofia@profesionales.com');
        $this->command->line('  Password para todos: password');
        $this->command->info('');
        $this->command->warn('CLIENTES:');
        $this->command->line('  roberto@clientes.com');
        $this->command->line('  carmen@clientes.com');
        $this->command->line('  david@clientes.com');
        $this->command->line('  elena@clientes.com');
        $this->command->line('  francisco@clientes.com');
        $this->command->line('  Password para todos: password');
        $this->command->info('');
        $this->command->info('========================================');
    }
}

