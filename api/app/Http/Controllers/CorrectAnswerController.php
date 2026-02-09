<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class CorrectAnswerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $questionId = $request->question_id;

        $answer = Answer::where([['question_id', '=', $questionId], ['is_correct', '=', 1]])->first();

        return response()->json([
            'success' => true,
            'correct_answe_id' => $answer->id,
        ]);
    }
}
