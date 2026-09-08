<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::factory()->create();
        $this->car = Car::factory()->create(['preco' => 15000000]);
    }

    public function test_guest_cannot_buy_car(): void
    {
        $this->post(route('cars.buy', $this->car->id))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_buy_a_car(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('cars.buy', $this->car->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'car_id' => $this->car->id,
            'preco_compra' => 15000000,
        ]);
    }

    public function test_user_cannot_buy_same_car_twice(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('cars.buy', $this->car->id));

        $this->actingAs($user)
            ->post(route('cars.buy', $this->car->id))
            ->assertSessionHas('error');

        $this->assertEquals(1, Purchase::where('user_id', $user->id)->where('car_id', $this->car->id)->count());
    }

    public function test_user_cannot_delete_others_purchase(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $purchase = Purchase::create([
            'user_id' => $owner->id,
            'car_id' => $this->car->id,
            'preco_compra' => $this->car->preco,
        ]);

        $this->actingAs($other)
            ->delete(route('purchases.destroy', $purchase->id))
            ->assertForbidden();

        $this->assertDatabaseHas('purchases', ['id' => $purchase->id]);
    }

    public function test_user_can_delete_own_purchase(): void
    {
        $user = User::factory()->create();

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'car_id' => $this->car->id,
            'preco_compra' => $this->car->preco,
        ]);

        $this->actingAs($user)
            ->delete(route('purchases.destroy', $purchase->id))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('purchases', ['id' => $purchase->id]);
    }
}
