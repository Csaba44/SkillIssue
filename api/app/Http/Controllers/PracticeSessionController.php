<?php

namespace App\Http\Controllers;

use App\Models\PracticeSession;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class PracticeSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = $request->user()->id;
        $userXp = $request->user()->xp;

        $ROUNDS = config('app.max_rounds');


        $practiceSession = PracticeSession::create([
            "user_id" => $userId,
            "rounds" => $ROUNDS,
            "xp_before" => $userXp,
        ]);

        $payload = [
            'user_id' => $userId,
            'rounds' => $ROUNDS,
            'session_id' => $practiceSession->id,
            'iat'         => time(),
            'exp'         => time() + 3600,
        ];


        $token = JWT::encode(
            $payload,
            config('app.key'),
            'HS256'
        );

        return response()->json([
            "success" => true,
            "game_token" => $token,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
