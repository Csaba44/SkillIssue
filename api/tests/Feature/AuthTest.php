<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Teszt Elek',
            'email' => 'elek@teszt.hu',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'A fiók sikeresen létrehozva.']);

        $this->assertDatabaseHas('users', ['email' => 'elek@teszt.hu']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Üdvözöljük!']);

        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('user_logins', ['user_id' => $user->id]);
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'rossz-jelszo',
        ]);

        $response->assertStatus(401);
        $this->assertGuest();
    }
}
