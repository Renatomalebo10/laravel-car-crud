<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_user_is_identified_correctly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $regular = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($regular->isAdmin());
    }

    #[Test]
    public function new_users_default_to_regular_role(): void
    {
        $user = User::factory()->create();

        $this->assertSame('user', $user->fresh()->role);
        $this->assertFalse($user->isAdmin());
    }
}