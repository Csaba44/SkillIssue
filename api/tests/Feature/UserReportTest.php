<?php

namespace Tests\Feature;

use App\Models\GameMatch;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_report_with_valid_token()
    {
        $user = User::factory()->create();
        $match = GameMatch::factory()->create(['user_id' => $user->id]);

        $rankedToken = JWT::encode([
            'match_uuid' => $match->match_uuid,
            'user_a_id'  => $user->id,
            'user_b_id'  => 0,
            'iat'        => time(),
            'exp'        => time() + 3600,
        ], config('app.key'), 'HS256');

        $response = $this->withToken($user->createToken('test')->plainTextToken)
            ->postJson('/api/user-reports', [
                'match_uuid'   => $match->match_uuid,
                'round_number' => 1,
                'comment'      => 'Ez egy teszt jelentés indoklása.',
                'ranked_token' => $rankedToken,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('user_reports', [
            'user_id'       => $user->id,
            'game_match_id' => $match->id,
            'comment'       => 'Ez egy teszt jelentés indoklása.',
        ]);
    }
}
