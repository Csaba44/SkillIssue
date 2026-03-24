<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureReportQuestionTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->input('question_token');

        if (!$token) {
            return response()->json(['message' => 'Missing question token.'], 403);
        }

        try {
            $payload = JWT::decode($token, new Key(config('app.key'), 'HS256'));
            $payload = (array) $payload;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid or expired token.'], 403);
        }

        $userId = $request->user()->id;

        $authorized = false;

        if (isset($payload['user_id'])) {
            $userIds = (array) $payload['user_id'];
            $authorized = in_array($userId, $userIds);
        }

        if (!$authorized && isset($payload['user_a_id'], $payload['user_b_id'])) {
            $authorized = in_array($userId, [$payload['user_a_id'], $payload['user_b_id']]);
        }

        if (!$authorized) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->merge(['question_id' => $payload['question_id']]);

        return $next($request);
    }
}
