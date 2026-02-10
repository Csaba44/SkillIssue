<?php

namespace App\Http\Middleware;

use App\Models\PracticeSession;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePracticeSessionTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validated = $request->validate([
            'game_token' => 'required|string',
        ]);


        $userId = $request->user()->id;

        try {
            $decodedGameToken = JWT::decode(
                $request->game_token,
                new Key(config('app.key'), 'HS256')
            );

            $decoded_game_user_id = $decodedGameToken->user_id;

            if ($decoded_game_user_id != $userId) {
                return response()->json([
                    'success' => false,
                    'error' => 'A játék nem a te fiókodhoz tartozik.'
                ], 403);
            }

            $session = PracticeSession::with("sessionQuestions")->find($decodedGameToken->session_id);

            $exclude = [];

            if ($session["sessionQuestions"] && count($session["sessionQuestions"]) > 0) {
                foreach ($session["sessionQuestions"] as $k => $v) {
                    $exclude[] = $v["question_id"];
                }
            }


            $request->merge(['session_id' => $decodedGameToken->session_id, 'exclude' => $exclude]);
            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'A játék nem a te fiókodhoz tartozik.'
            ], 403);
        }
    }
}
