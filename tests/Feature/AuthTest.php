<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_login_page(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Inicie sessão');
    }

    public function test_guest_sees_register_page(): void
    {
        $this->get(route('register'))->assertOk()->assertSee('Crie a sua conta');
    }

    public function test_new_user_can_register(): void
    {
        $response = $this->post(route('register'), [
            'name'                  => 'Novo Utilizador',
            'email'                 => 'novo@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'novo@example.com',
            'role'  => 'user',
        ]);

        $this->assertAuthenticated();
    }

    public function test_register_requires_password_confirmation(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Novo Utilizador',
            'email'                 => 'novo@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'diferente',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $response = $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_credentials_fails(): void
    {
        $this->post(route('login'), [
            'email'    => 'inexistente@example.com',
            'password' => 'errada123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect(route('dashboard'));
    }
}