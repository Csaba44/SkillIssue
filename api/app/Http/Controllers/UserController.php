<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\GameMatch;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display current user
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $levelAttribute = $user->getLevelAttribute();
        $rankAttribute = $user->getRankAttribute();

        $userData = array_merge($user->toArray(), [
            'ban_status' => $user->getIsBannedAttribute(),
            'level' => $levelAttribute,
            'rank' => $rankAttribute,
            'next_level' => $user->getNextLevelAttribute() ?? $levelAttribute,
            'next_rank' => $user->getNextRankAttribute() ?? $rankAttribute,
            'top_ranking' => $user->getPlayerTopPercentileAttribute(),
            'matches_played' => $user->getPlayedMatchesCountAttribute(),
            'streak_count' => $user->getStreakAttribute()
        ]);
        return response()->json($userData);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update([
            "name" => $request["name"],
            "email" => $request["email"]
        ]);

        return response()->json(["message" => "Felhasználó sikeresen frissítve.", "user" => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRequest $request, User $user)
    {
        $user->delete();

        return response()->json(["message" => "Felhasználó sikeresen törölve."]);
    }
}
