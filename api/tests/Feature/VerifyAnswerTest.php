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
        // 1. Arrange: Adatok előkészítése
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $matchUuid = 'test-match-uuid-123';

        // Match-ek és Subject/Question
        $matchA = GameMatch::factory()->create(['match_uuid' => $matchUuid, 'user_id' => $userA->id]);
        $matchB = GameMatch::factory()->create(['match_uuid' => $matchUuid, 'user_id' => $userB->id]);
        
        // Middleware elvárja a session-t
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

        // Question Token (EnsureQuestionTokenIsValid)
        $questionToken = JWT::encode([
            'user_id' => [$userA->id], 
            'question_id' => $question->id
        ], $appKey, 'HS256');

        // Game Token (EnsurePracticeSessionTokenIsValid)
        $gameToken = JWT::encode([
            'user_id' => $userA->id, 
            'session_id' => $session->id
        ], $appKey, 'HS256');

        // 2. Act: POST kérés küldése
        $response = $this->actingAs($userA)->postJson("/api/answers/verify/{$correctAnswer->id}", [
            'answering_user_id' => $userA->id,
            'user_a_id' => $userA->id,
            'user_b_id' => $userB->id,
            'match_uuid' => $matchUuid,
            'question_id' => $question->id,
            
            // Middleware-ek által elvárt mezők
            'question_token' => $questionToken,
            'practice_session_token' => 'dummy-token', 
            'game_token' => $gameToken,
        ]);

        // 3. Assert: Ellenőrzés
        $response->assertStatus(200);
        $response->assertJsonFragment(['is_correct' => true]);
        
        $this->assertDatabaseHas('match_questions', [
            'id' => $mqA->id,
            'user_answer_id' => $correctAnswer->id,
        ]);
    }
}