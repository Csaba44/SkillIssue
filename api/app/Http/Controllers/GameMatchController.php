<?php

namespace App\Http\Controllers;

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
        $usersMatches = GameMatch::with(['user', 'opponent'])->where('user_id', '=', $userId)->get();

        return response()->json($usersMatches);
    }

    /**
     * Display the specified resource.
     */
    public function show(GameMatchRequest $request, string $id)
    {
        $match = GameMatch::with(['user', 'opponent'])->where('id', '=', $id)->get()->first();

        // Admins can view any resource
        if ($match->user_id !== $request->user()->id && !$request->user()->is_admin) {
            return response()->json(["message" => "Nincs engedélye megtekinteni az erőforrást."]);
        }

        return response()->json($match);
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
