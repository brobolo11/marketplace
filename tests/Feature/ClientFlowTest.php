<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test de flujo completo de cliente:
     * 1. Buscar servicio
     * 2. Ver detalles
     * 3. Crear reserva
     * 4. Ver reserva
     * 5. Dejar reseña
     */
    public function test_cliente_puede_completar_flujo_de_reserva_y_resena(): void
    {
        // 1. Crear usuarios
        $cliente = User::factory()->create(['role' => 'client']);
        $profesional = User::factory()->create(['role' => 'pro']);

        // 2. Crear categoría y servicio
        $categoria = Category::create([
            'name' => 'Fontanería',
            'description' => 'Servicios de fontanería',
            'icon' => 'fa-wrench',
        ]);

        $servicio = Service::create([
            'user_id' => $profesional->id,
            'category_id' => $categoria->id,
            'title' => 'Reparación de tuberías',
            'description' => 'Reparación profesional de fugas',
            'price_hour' => 50.00,
            'duration' => 60,
        ]);

        // 3. Cliente busca servicios (GET /)
        $response = $this->actingAs($cliente)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Reparación de tuberías');

        // 4. Cliente ve detalles del servicio
        $response = $this->actingAs($cliente)->get(route('services.show', $servicio));
        $response->assertStatus(200);
        $response->assertSee('Reparación de tuberías');
        $response->assertSee('50.00');

        // 5. Cliente crea reserva
        $fechaReserva = now()->addDays(3)->format('Y-m-d H:i:s');
        $response = $this->actingAs($cliente)->post(route('bookings.store'), [
            'service_id' => $servicio->id,
            'datetime' => $fechaReserva,
            'address' => 'Calle Principal 123',
            'total_price' => 50.00,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'user_id' => $cliente->id,
            'pro_id' => $profesional->id,
            'service_id' => $servicio->id,
            'status' => 'pending',
        ]);

        // 6. Cliente ve su reserva
        $reserva = Booking::first();
        $response = $this->actingAs($cliente)->get(route('bookings.show', $reserva));
        $response->assertStatus(200);
        $response->assertSee('Reparación de tuberías');

        // 7. Simular que la reserva es completada
        $reserva->update(['status' => 'completed']);

        // 8. Cliente deja reseña
        $response = $this->actingAs($cliente)->post(route('reviews.store'), [
            'booking_id' => $reserva->id,
            'rating' => 5,
            'comment' => 'Excelente servicio',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $cliente->id,
            'pro_id' => $profesional->id,
            'booking_id' => $reserva->id,
            'rating' => 5,
        ]);
    }

    /**
     * Test: Cliente no puede reservar su propio servicio
     */
    public function test_cliente_no_puede_reservar_su_propio_servicio(): void
    {
        $usuario = User::factory()->create(['role' => 'pro']);
        $categoria = Category::create([
            'name' => 'Electricidad',
            'description' => 'Servicios eléctricos',
            'icon' => 'fa-bolt',
        ]);

        $servicio = Service::create([
            'user_id' => $usuario->id,
            'category_id' => $categoria->id,
            'title' => 'Instalación eléctrica',
            'description' => 'Instalación de circuitos',
            'price_hour' => 75.00,
            'duration' => 120,
        ]);

        $response = $this->actingAs($usuario)->post(route('bookings.store'), [
            'service_id' => $servicio->id,
            'datetime' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'address' => 'Calle Test 456',
            'total_price' => 75.00,
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $usuario->id,
            'service_id' => $servicio->id,
        ]);
    }

    /**
     * Test: Cliente no puede dejar reseña dos veces
     */
    public function test_cliente_no_puede_dejar_dos_resenas_misma_reserva(): void
    {
        $cliente = User::factory()->create(['role' => 'client']);
        $profesional = User::factory()->create(['role' => 'pro']);
        $categoria = Category::create([
            'name' => 'Carpintería',
            'description' => 'Servicios de carpintería',
            'icon' => 'fa-hammer',
        ]);

        $servicio = Service::create([
            'user_id' => $profesional->id,
            'category_id' => $categoria->id,
            'title' => 'Montaje de muebles',
            'description' => 'Montaje profesional',
            'price_hour' => 40.00,
            'duration' => 90,
        ]);

        $reserva = Booking::create([
            'user_id' => $cliente->id,
            'pro_id' => $profesional->id,
            'service_id' => $servicio->id,
            'datetime' => now()->addDays(1),
            'address' => 'Av. Central 789',
            'status' => 'completed',
            'total_price' => 40.00,
        ]);

        // Primera reseña
        Review::create([
            'booking_id' => $reserva->id,
            'user_id' => $cliente->id,
            'pro_id' => $profesional->id,
            'rating' => 4,
            'comment' => 'Buen trabajo',
        ]);

        // Intentar segunda reseña
        $response = $this->actingAs($cliente)->post(route('reviews.store'), [
            'booking_id' => $reserva->id,
            'rating' => 5,
            'comment' => 'Otra reseña',
        ]);

        $response->assertSessionHasErrors();
        $this->assertEquals(1, Review::where('booking_id', $reserva->id)->count());
    }
}
