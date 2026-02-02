<?php

namespace App\Http\Controllers;

use App\Models\PracticeSessionQuestion;
use App\Models\Question;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SingleQuestionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validated = $request->validate([
                "game_token" => "required|string"
            ]);


            $decodedGameToken = JWT::decode(
                $validated["game_token"],
                new Key(config('app.key'), 'HS256')
            );

            $tokenUserId = $decodedGameToken->user_id;
            $tokenSessionId = $decodedGameToken->session_id;

            if ($request->user()->id !== $tokenUserId) {
                return response()->json(["message" => "A játszma nem a te fiókodhoz tartozik."], 403);
            }



            $excludedQuestions = PracticeSessionQuestion::where(
                'practice_session_id',
                $tokenSessionId
            )
                ->pluck('question_id');


            $randomQuestion = Question::with(['subject', 'answers'])
                ->whereNotIn('id', $excludedQuestions)
                ->get()
                ->random();


            $userId = $request->user()->id;

            $payload = [
                'question_id' => $randomQuestion->id,
                'user_id'  => [$userId],
                'iat'         => time(),
                'exp'         => time() + 9999, // 45 seconds
            ];


            $token = JWT::encode(
                $payload,
                config('app.key'),
                'HS256'
            );

            $randomQuestion->answers->makeVisible('is_correct');
            $correctAnswerId = $randomQuestion->answers
                ->firstWhere('is_correct', 1)
                ->id;
            $randomQuestion->answers->makeHidden('is_correct');


            $lastRound = PracticeSessionQuestion::where('practice_session_id', $tokenSessionId)
                ->max('round_number');

            $currentRound = ($lastRound ?? 0) + 1;

            PracticeSessionQuestion::create([
                'practice_session_id' => $tokenSessionId,
                'question_id' => $randomQuestion->id,
                'correct_answer_id' => $correctAnswerId,
                'round_number' => $currentRound,
            ]);


            return response()->json([
                'success' => true,
                'question' => $randomQuestion,
                'token' => $token,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'question' => [],
                'error' => 'Nincs elegendő kérdés az adatbázisban.',
            ]);
        }
    }
}
