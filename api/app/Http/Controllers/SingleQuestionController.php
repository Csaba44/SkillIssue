<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class SingleQuestionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $excludedQuestions = $request->exclude;
        $excludedQuestions = explode(',', $excludedQuestions);


        try {
            $randomQuestion = Question::with('subject')->whereNotIn('id', $excludedQuestions)->get()->random(1);
            $randomQuestion = $randomQuestion[0];

            return response()->json([
                'success' => true,
                'question' => $randomQuestion,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'question' => [],
                'error' => 'Nincs elegendő kérdés az adatbázisban.',
            ]);
        }
    }
}
