<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_cannot_access_admin_route()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $token = $user->createToken('test-token')->plainTextToken;
        $response = $this->withToken($token)->getJson('/api/subjects');

        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_admin_route()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $token = $admin->createToken('test-token')->plainTextToken;
        $response = $this->withToken($token)->getJson('/api/subjects');

        $response->assertStatus(200);
    }
}
