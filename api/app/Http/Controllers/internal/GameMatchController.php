<?php

namespace App\Http\Controllers\internal;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GameMatchController extends Controller
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
        $validated = $request->validate([
            'user_a_id' => 'required|integer|exists:users,id|different:user_b_id',
            'user_b_id' => 'required|integer|exists:users,id',
        ]);



        return DB::transaction(function () use ($validated) {

            $userA = User::lockForUpdate()->findOrFail($validated['user_a_id']);
            $userB = User::lockForUpdate()->findOrFail($validated['user_b_id']);

            $banStatusA = $userA->getIsBannedAttribute();
            $banStatusB = $userB->getIsBannedAttribute();

            if ($banStatusA || $banStatusB) {
                return response()->json(["is_banned" => true, "message" => "Az egyik résztvevő játékos ki lett tiltva a SkillIssue alkalmazásból."], 403);
            }


            $uuid = (string) Str::uuid();



            $payload = [
                'match_uuid' => $uuid,
                'user_a_id' => $userA->id,
                'user_b_id' => $userB->id,
                'iat' => time(),
                'exp' => time() + 3600,
            ];

            $token = JWT::encode(
                $payload,
                config('app.key'),
                'HS256'
            );

            $expectedA = 1 / (1 + pow(10, ($userB->elo - $userA->elo) / 400));
            $expectedB = 1 - $expectedA;

            GameMatch::create([
                'user_id'           => $userA->id,
                'opponent_id'       => $userB->id,
                'elo_before'        => $userA->elo,
                'xp_before'         => $userA->xp,
                'match_uuid'        => $uuid,
                'expected_winrate'  => $expectedA,
            ]);

            GameMatch::create([
                'user_id'           => $userB->id,
                'opponent_id'       => $userA->id,
                'elo_before'        => $userB->elo,
                'xp_before'         => $userB->xp,
                'match_uuid'        => $uuid,
                'expected_winrate'  => $expectedB,
            ]);

            return response()->json([
                'user_a_name' => $userA->name,
                'user_b_name' => $userB->name,
                'match_uuid'  => $uuid,
                'ranked_token' => $token
            ], 201);
        });
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
