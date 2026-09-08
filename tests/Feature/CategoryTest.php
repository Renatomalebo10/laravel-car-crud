<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
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

    public function test_guest_is_redirected_from_category_management(): void
    {
        $this->get(route('categories.create'))->assertRedirect(route('login'));
    }

    public function test_admin_sees_category_management_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('categories.create'))
            ->assertOk()
            ->assertSee('Categorias Cadastradas');
    }

    public function test_regular_user_cannot_access_category_management(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('categories.create'))
            ->assertForbidden();
    }

    public function test_admin_can_create_a_category(): void
    {
        $this->actingAs($this->admin())
            ->post(route('categories.store'), ['nome' => 'SUV'])
            ->assertRedirect(route('categories.create'));

        $this->assertDatabaseHas('categories', ['nome' => 'SUV']);
    }

    public function test_duplicate_category_is_rejected(): void
    {
        Category::factory()->create(['nome' => 'Sedan']);

        $this->actingAs($this->admin())
            ->post(route('categories.store'), ['nome' => 'Sedan'])
            ->assertSessionHasErrors('nome');
    }

    public function test_admin_can_update_a_category(): void
    {
        $category = Category::factory()->create(['nome' => 'Antigo']);

        $this->actingAs($this->admin())
            ->put(route('categories.update', $category), ['nome' => 'Moderno'])
            ->assertRedirect(route('categories.create'));

        $this->assertDatabaseHas('categories', ['nome' => 'Moderno']);
        $this->assertDatabaseMissing('categories', ['nome' => 'Antigo']);
    }

    public function test_admin_can_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.create'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}