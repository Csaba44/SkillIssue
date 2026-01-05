<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class SingleQuestionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $excludedQuestions = $request->exclude;

        try {
            $randomQuestion = Question::with(['subject', 'answers'])->whereNotIn('id', $excludedQuestions)->get()->random(1);
            $randomQuestion = $randomQuestion[0];

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
