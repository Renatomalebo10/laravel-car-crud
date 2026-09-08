<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_dashboard(): void
    {
        Category::factory()->count(2)->create();
        Car::factory()->count(3)->create(['preco' => 10000000]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Carros Comprados')
            ->assertSee('Valor Total das Compras');
    }

    public function test_regular_user_does_not_see_inventory_totals(): void
    {
        Category::factory()->count(2)->create();
        Car::factory()->count(3)->create(['preco' => 10000000]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('30.000.000,00 KZ');
    }

    public function test_regular_user_sees_their_purchases_stats(): void
    {
        Category::factory()->create();
        $car1 = Car::factory()->create(['preco' => 10000000]);
        $car2 = Car::factory()->create(['preco' => 20000000]);
        $user = User::factory()->create();

        $user->purchases()->create(['car_id' => $car1->id, 'preco_compra' => $car1->preco]);
        $user->purchases()->create(['car_id' => $car2->id, 'preco_compra' => $car2->preco]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Carros Comprados')
            ->assertSee('30.000.000,00 KZ');
    }

    public function test_dashboard_is_empty_state_without_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('0')
            ->assertSee('Você ainda não comprou nenhum carro');
    }

    public function test_admin_sees_inventory_dashboard(): void
    {
        Category::factory()->count(2)->create();
        Car::factory()->count(3)->create(['preco' => 10000000]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Veículos')
            ->assertSee('3')
            ->assertSee('30.000.000,00 KZ');
    }
}