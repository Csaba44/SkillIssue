<?php

namespace App\Http\Middleware;

use App\Models\GameMatch;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRankedTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validated = $request->validate([
            'ranked_token' => 'required|string'
        ]);

        try {
            $decoded = JWT::decode(
                $validated["ranked_token"],
                new Key(config('app.key'), 'HS256')
            );

            /*
            Structure: 
            'match_uuid' => $uuid,
            'user_a_id' => $userA->id,
            'user_b_id' => $userB->id,
            'iat' => time(),
            'exp' => time() + 3600,
            */

            $gameMatches = GameMatch::where('match_uuid', $decoded->match_uuid)->get();


            // Append data to request
            $request->merge([
                'match_uuid' => $decoded->match_uuid,
                'user_a_id' => $decoded->user_a_id,
                'user_b_id' => $decoded->user_b_id
            ]);
            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Helytelen játszma azonosító.'
            ], 403);
        }
        return $next($request);
    }
}
