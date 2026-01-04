<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class VerifyAnswer extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Answer $answer)
    {
        return response()->json([
            'success' => true,
            'is_correct' => $answer->is_correct,
        ], 200);
    }
}
