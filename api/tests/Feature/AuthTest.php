<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

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

    public function test_user_cannot_register_with_existing_email()
    {
        $existingUser = User::factory()->create([
            'email' => 'foglalt@gmail.com'
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'joska',
            'email' => 'foglalt@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_cannot_register_with_invalid_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'joska',
            'email' => 'hibas-email-cim',
            'password' => 'password123'
        ]);

        $response->assertStatus(422);
    }

    public function test_user_cannot_register_with_short_password()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'józsef',
            'email' => 'teszt@gmail.com',
            'password' => '123'
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/logout');
        $response->assertStatus(200);

        Auth::forgetUser();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
        $protectedResponse = $this->getJson('/api/users');

        $protectedResponse->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_access_protected_route()
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
    }
}
