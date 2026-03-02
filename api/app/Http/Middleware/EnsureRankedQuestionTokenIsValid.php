<?php

namespace App\Http\Middleware;

use App\Models\Question;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRankedQuestionTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validated = $request->validate([
            'question_token' => 'required|string',
        ]);
        $userAId = $request->user_a_id;
        $userBId = $request->user_b_id;


        try {
            $decoded = JWT::decode(
                $validated['question_token'],
                new Key(config('app.key'), 'HS256')
            );


            $decoded_user_a_id = $decoded->user_a_id;
            $decoded_user_b_id = $decoded->user_b_id;


            if ($decoded_user_a_id != $userAId && $decoded_user_b_id != $userBId) {
                return response()->json([
                    'success' => false,
                    'error' => 'A kérdés nem a te fiókodhoz tartozik.'
                ], 403);
            }


            $request->merge(['question_id' => $decoded->question_id]);
            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'A kérdés nem a te fiókodhoz tartozik.'
            ], 403);
        }
    }
}
