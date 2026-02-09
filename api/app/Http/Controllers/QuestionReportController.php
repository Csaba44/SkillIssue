<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionReportRequest;
use App\Models\QuestionReport;
use Illuminate\Http\Request;

class QuestionReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QuestionReportRequest $request)
    {
        $reports = QuestionReport::with(["user", "question"])->get();

        return response()->json($reports);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionReportRequest $request)
    {
        $userId = $request->user()->id;

        $report = QuestionReport::create([
            "user_id" => $userId,
            "question_id" => $request["question_id"],
            "comment" => $request["comment"],
        ]);

        return response()->json(["message" => "A bejelentés sikeresen létrehozva. Köszönjük!", "report" => $report], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionReportRequest $request, string $id)
    {
        $report = QuestionReport::with(["user", "question"])
            ->findOrFail($id);
        return response()->json($report);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionReportRequest $request, QuestionReport $questionReport)
    {
        $questionReport->update($request->all());

        return response()->json(["message" => "A bejelentés sikeresen frissítve.", "report" => $questionReport]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionReportRequest $request, QuestionReport $questionReport)
    {
        $questionReport->delete();

        return response()->json(["message" => "A bejelentés sikeresen törölve."]);
    }
}
