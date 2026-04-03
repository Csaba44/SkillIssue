<?php

namespace App\Http\Controllers;

use App\GameResultEnum;
use App\Http\Requests\GameMatchRequest;
use App\Models\GameMatch;
use Illuminate\Http\Request;

class GameMatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GameMatchRequest $request)
    {
        $userId = $request->user()->id;

        $usersMatches = GameMatch::with(['user', 'opponent'])
            ->where('user_id', $userId)
            ->whereNotIn('match_result', [
                GameResultEnum::PENDING->value,
                GameResultEnum::CANCELLED->value
            ])
            ->get();

        return response()->json($usersMatches);
    }
    /**
     * Display the specified resource.
     */
    public function show(GameMatchRequest $request, string $id)
    {
        $matches = GameMatch::with(['user', 'opponent'])
            ->where('match_uuid', $id)
            ->whereNotIn('match_result', [
                GameResultEnum::PENDING->value,
                GameResultEnum::CANCELLED->value
            ])
            ->get();

        if ($matches->count() !== 2) {
            return response()->json(['error' => 'Meccs nem található.'], 404);
        }

        $matchA = $matches[0];
        $matchB = $matches[1];

        $isDraw = $matchA->match_result === GameResultEnum::DRAW;

        return response()->json([
            'isDraw'  => $isDraw,
            'playerA' => [
                'userId'    => $matchA->user_id,
                'userName'  => $matchA->user->name,
                'won'       => $isDraw ? null : $matchA->match_result === GameResultEnum::WIN,
                'eloChange' => $matchA->elo_after !== null ? $matchA->elo_after - $matchA->elo_before : null,
                'score'     => $matchA->questions()->whereColumn('user_answer_id', 'correct_answer_id')->count(),
            ],
            'playerB' => [
                'userId'    => $matchB->user_id,
                'userName'  => $matchB->user->name,
                'won'       => $isDraw ? null : $matchB->match_result === GameResultEnum::WIN,
                'eloChange' => $matchB->elo_after !== null ? $matchB->elo_after - $matchB->elo_before : null,
                'score'     => $matchB->questions()->whereColumn('user_answer_id', 'correct_answer_id')->count(),
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameMatchRequest $request, GameMatch $gameMatch)
    {
        $gameMatch->delete();
        return response()->json(["message" => "A játszma sikeresen törölve."]);
    }
}
