<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureServiceTokenIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token || ! hash_equals(config('services.internal.token'), $token)) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized service request',
            ], 401);
        }

        return $next($request);
    }
}
