<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function regularUser(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    public function test_guest_is_redirected_from_inventory(): void
    {
        $this->get(route('cars.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_inventory(): void
    {
        $user = $this->regularUser();
        Car::factory()->count(3)->create();

        $this->actingAs($user)
            ->get(route('cars.index'))
            ->assertOk()
            ->assertSee('Inventário de Carros');
    }

    public function test_admin_can_create_a_car(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->post(route('cars.store'), [
                'category_id' => $category->id,
                'marca'       => 'Toyota',
                'modelo'      => 'Land Cruiser',
                'cor'         => 'Branco',
                'ano'         => 2024,
                'placa'       => 'LD-12-34-AB',
                'preco'       => '25,5',
            ])
            ->assertRedirect(route('cars.index'));

        $this->assertDatabaseHas('cars', [
            'marca'  => 'Toyota',
            'modelo' => 'Land Cruiser',
            'placa'  => 'LD-12-34-AB',
            'preco'  => 25.50,
        ]);
    }

    public function test_regular_user_cannot_create_a_car(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->regularUser())
            ->post(route('cars.store'), [
                'category_id' => $category->id,
                'marca'       => 'Toyota',
                'modelo'      => 'Hilux',
                'cor'         => 'Preto',
                'ano'         => 2023,
                'placa'       => 'LU-98-76-CD',
                'preco'       => 10000000,
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('cars', 0);
    }

    public function test_duplicate_placa_is_rejected(): void
    {
        $category = Category::factory()->create();
        Car::factory()->create(['placa' => 'LD-12-34-AB', 'category_id' => $category->id]);

        $this->actingAs($this->admin())
            ->post(route('cars.store'), [
                'category_id' => $category->id,
                'marca'       => 'Toyota',
                'modelo'      => 'Hilux',
                'cor'         => 'Preto',
                'ano'         => 2023,
                'placa'       => 'LD-12-34-AB',
                'preco'       => 10000000,
            ])
            ->assertSessionHasErrors('placa');
    }

    public function test_admin_can_update_a_car(): void
    {
        $car = Car::factory()->create();

        $this->actingAs($this->admin())
            ->put(route('cars.update', $car), [
                'category_id' => $car->category_id,
                'marca'       => 'Nissan',
                'modelo'      => 'X-Trail',
                'cor'         => 'Vermelho',
                'ano'         => 2022,
                'placa'       => $car->placa,
                'preco'       => 15000000,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('cars', [
            'id'     => $car->id,
            'marca'  => 'Nissan',
            'modelo' => 'X-Trail',
        ]);
    }

    public function test_admin_can_delete_a_car(): void
    {
        Storage::fake('public');
        $car = Car::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('cars.destroy', $car))
            ->assertRedirect(route('cars.index'));

        $this->assertDatabaseMissing('cars', ['id' => $car->id]);
    }

    public function test_search_filters_inventory(): void
    {
        $this->actingAs($this->regularUser());

        Car::factory()->create(['marca' => 'Toyota']);
        Car::factory()->create(['marca' => 'Honda']);

        $this->get(route('cars.index', ['q' => 'Toyota']))
            ->assertSee('Toyota')
            ->assertDontSee('Honda');
    }
}