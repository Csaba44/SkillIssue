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

    public function test_user_cannot_delete_game_match()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $match = \App\Models\GameMatch::factory()->create();
        $response = $this->withToken($user->createToken('test')->plainTextToken)
            ->deleteJson("/api/game-matches/{$match->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('game_matches', ['id' => $match->id]);
    }

    public function test_admin_can_delete_game_match()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $match = \App\Models\GameMatch::factory()->create();
        $response = $this->withToken($admin->createToken('test')->plainTextToken)
            ->deleteJson("/api/game-matches/{$match->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('game_matches', ['id' => $match->id]);
    }

    public function test_admin_can_ban_a_user()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $targetUser = User::factory()->create();

        $payload = [
            'user_id' => $targetUser->id,
            'reason' => 'Szabályszegés',
            'release_date' => now()->addDays(7)->toDateTimeString(),
        ];

        $response = $this->withToken($admin->createToken('test')->plainTextToken)
            ->postJson('/api/ban', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('bans', ['user_id' => $targetUser->id]);
    }

    public function test_admin_cannot_create_subject_with_empty_name()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $payload = ['name' => ''];

        $response = $this->withToken($admin->createToken('test')->plainTextToken)
            ->postJson('/api/subjects', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_admin_can_update_subject_details()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $subject = \App\Models\Subject::factory()->create(['name' => 'Régi név']);

        $response = $this->withToken($admin->createToken('test')->plainTextToken)
            ->putJson("/api/subjects/{$subject->id}", ['name' => 'Új név']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'name' => 'Új név'
        ]);
    }
}
