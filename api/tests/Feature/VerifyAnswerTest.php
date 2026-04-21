<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\GameMatch;
use App\Models\MatchQuestion;
use App\Models\PracticeSession;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerifyAnswerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_an_answer_to_a_question()
    {
        // 1: Adatok előkészítése
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $matchUuid = 'test-match-uuid-123';
        $matchA = GameMatch::factory()->create(['match_uuid' => $matchUuid, 'user_id' => $userA->id]);
        $matchB = GameMatch::factory()->create(['match_uuid' => $matchUuid, 'user_id' => $userB->id]);

        $session = PracticeSession::factory()->create(['user_id' => $userA->id]);

        $subject = Subject::factory()->create();
        $question = Question::factory()->create(['subject_id' => $subject->id]);

        $correctAnswer = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true
        ]);

        $mqA = MatchQuestion::factory()->create([
            'game_match_id' => $matchA->id,
            'question_id' => $question->id,
            'correct_answer_id' => $correctAnswer->id,
        ]);

        // JWT tokenek generálása a middleware-eknek (app.key használatával)
        $appKey = config('app.key');

        //EnsureQuestionTokenIsValid MW
        $questionToken = JWT::encode([
            'user_a_id' => $userA->id,
            'user_b_id' => $userB->id,
            'question_id' => $question->id
        ], config('app.key'), 'HS256');

        //EnsurePracticeSessionTokenIsValid MW
        $gameToken = JWT::encode([
            'user_id' => $userA->id,
            'session_id' => $session->id
        ], $appKey, 'HS256');

        //ENsureRankedTokenIsValid MW
        $rankedToken = JWT::encode([
            'match_uuid' => $matchUuid,
            'user_a_id' => $userA->id,
            'user_b_id' => $userB->id,
            'iat' => time(),
            'exp' => time() + 3600,
        ], config('app.key'), 'HS256');

        //EnsureServiceTokenIsValid MW
        $serviceToken = 'abcdefg123456';
        config(['services.internal.token' => $serviceToken]);


        // 2: POST kérés küldése
        $response = $this->withHeader('Authorization', 'Bearer ' . $serviceToken)
            ->postJson("/api/internal/answers/verify/{$correctAnswer->id}", [
                'answering_user_id' => $userA->id,
                'user_a_id' => $userA->id,
                'user_b_id' => $userB->id,
                'match_uuid' => $matchUuid,
                'question_id' => $question->id,

                'ranked_token' => $rankedToken,
                'question_token' => $questionToken,
                'practice_session_token' => 'dummy-token',
                'game_token' => $gameToken,
            ]);


        // 3: Ellenőrzés
        $response->assertStatus(200);
        $response->assertJsonFragment(['is_correct' => true]);

        $this->assertDatabaseHas('match_question', [
            'id' => $mqA->id,
            'user_answer_id' => $correctAnswer->id,
        ]);
    }
}
