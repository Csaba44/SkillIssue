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

        $userMatchesCount = $user->gameMatches()->count();
        $userPracticesCount = $user->practiceSessions()->count();

        $allMatchesCount = $userMatchesCount + $userPracticesCount;

        $allUsersCount = User::count();

        if ($allUsersCount === 0) {
            $topPercentRounded = 1;
        } else {
            $usersCountWithLessMatches = User::withCount([
                'gameMatches',
                'practiceSessions'
            ])
                ->get()
                ->filter(function ($u) use ($allMatchesCount) {
                    return ($u->game_matches_count + $u->practice_sessions_count) < $allMatchesCount;
                })
                ->count();

            $percentile = ($usersCountWithLessMatches / $allUsersCount) * 100;
            $topPercent = 100 - $percentile;

            $topPercentRounded = (int) ceil($topPercent);
        }

        $userData = array_merge($user->toArray(), [
            'level' => $user->getLevelAttribute(),
            'rank' => $user->getRankAttribute(),
            'next_level' => $user->getNextLevelAttribute(),
            'next_rank' => $user->getNextRankAttribute(),
            //'game_matches' => $user->gameMatches,
            'top_ranking' => $topPercentRounded,
            'matches_played' => $allMatchesCount,
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
