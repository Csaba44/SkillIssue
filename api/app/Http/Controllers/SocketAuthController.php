<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SocketAuthController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['authenticated' => false], 401);
        }

        return response()->json([
            'authenticated' => true,
            'user' => $request->user()
        ]);
    }
}
