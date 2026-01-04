<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureQuestionTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validated = $request->validate([
            'question_token' => 'required|string'
        ]);


        $userId = $request->user()->id;

        try {
            $decoded = JWT::decode(
                $request->question_token,
                new Key(config('app.key'), 'HS256')
            );

            $decoded_user_id = $decoded->user_id;

            if (!in_array($userId, $decoded_user_id)) {
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
