<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $matches = $user->gameMatches()->orderBy('created_at', 'asc')->get();
        $matches->makeHidden(['updated_at','deleted_at','match_uuid']);

        $levelAttribute = $user->getLevelAttribute();
        $rankAttribute = $user->getRankAttribute();

        $user->makeHidden(['id', 'email', 'email_verified_at', 'is_admin', 'updated_at', 'deleted_at']);

        $userData = array_merge($user->toArray(), [
            'level' => $levelAttribute,
            'rank' => $rankAttribute,
            'next_level' => $user->getNextLevelAttribute() ?? $levelAttribute,
            'next_rank' => $user->getNextRankAttribute() ?? $rankAttribute,
            'top_ranking' => $user->getPlayerTopPercentileAttribute(),
            'matches_played' => $user->getPlayedMatchesCountAttribute(),
            'streak_count' => $user->getStreakAttribute(),
            'winstreak_count'=>$user->getWinStreakAttribute(),
            'game_matches' => $matches
        ]);

        return response()->json($userData);
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
