<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserReportRequest;
use App\Models\GameMatch;
use App\Models\UserReport;
use Illuminate\Http\Request;

use function Symfony\Component\String\b;

class UserReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserReportRequest $request)
    {
        $reports = UserReport::with(["userReporting", "userReported", "gameMatch"])->get();

        return response()->json($reports);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserReportRequest $request)
    {
        $userId = $request->user()->id;

        $gameMatch = GameMatch::where("user_id", $userId)->where("match_uuid", $request->match_uuid)->first();

        $report = UserReport::create([
            "user_id" => $userId,
            "game_match_id" => $gameMatch->id,
            "round_number" => $request["round_number"],
            "comment" => $request["comment"],
        ]);

        return response()->json(["message" => "A bejelentés sikeresen létrehozva. Köszönjük!", "report" => $report], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report = UserReport::with(["userReporting", "userReported", "gameMatch"])
            ->findOrFail($id);
        return response()->json($report);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserReportRequest $request, UserReport $userReport)
    {
        $userReport->update($request->all());

        return response()->json(["message" => "A bejelentés sikeresen frissítve.", "report" => $userReport]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserReportRequest $request, UserReport $userReport)
    {
        $userReport->delete();

        return response()->json(["message" => "A bejelentés sikeresen törölve."]);
    }
}
