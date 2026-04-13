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
        $maxRounds = config('app.max_rounds');


        try {
            $userId = $request->user()->id;

            $tokenSessionId = $request->session_id;

            // Get the previous questions, that we will exclude when fetching the database
            $excludedQuestions = PracticeSessionQuestion::where(
                'practice_session_id',
                $tokenSessionId
            )
                ->pluck('question_id');


            $randomQuestion = Question::with(['subject', 'answers'])
                ->whereNotIn('id', $excludedQuestions)
                ->get()
                ->random();

            // Create a question token
            $payload = [
                'question_id' => $randomQuestion->id,
                'user_id'  => [$userId],
                'iat'         => time(),
                'exp'         => time() + 60, // seconds
            ];


            $token = JWT::encode(
                $payload,
                config('app.key'),
                'HS256'
            );

            // Get the correct answer (and make it hidden again)
            $randomQuestion->answers->makeVisible('is_correct');
            $correctAnswerId = $randomQuestion->answers
                ->firstWhere('is_correct', 1)
                ->id;
            $randomQuestion->answers->makeHidden('is_correct');
            $randomQuestion->setRelation('answers', $randomQuestion->answers->shuffle());


            // Get the current round's number
            $lastRound = PracticeSessionQuestion::where('practice_session_id', $tokenSessionId)
                ->max('round_number');

            $currentRound = ($lastRound ?? 0) + 1;


            // Get if it's the end of the session
            if ($currentRound > $maxRounds) {
                return response()->json([
                    'success' => false,
                    'error' => 'A játszma véget ért.',
                    'game_id' => $tokenSessionId
                ], 410);
            }

            // Add the question
            PracticeSessionQuestion::create([
                'practice_session_id' => $tokenSessionId,
                'question_id' => $randomQuestion->id,
                'correct_answer_id' => $correctAnswerId,
                'round_number' => $currentRound,
            ]);


            return response()->json([
                'success' => true,
                'current_round' => $currentRound,
                'question' => $randomQuestion,
                'question_token' => $token,
            ], 200);
        } catch (\Throwable $th) {
            Log::error('SingleQuestion error', [
                'exception' => $th,
            ]);

            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
