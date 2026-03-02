<?php

namespace App\Http\Controllers\internal;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\GameMatch;
use App\Models\MatchQuestion;
use App\Models\User;
use Illuminate\Http\Request;

class VerifyAnswerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Answer $answer)
    {
        $validated = $request->validate([
            "answering_user_id" => "required|integer|exists:users,id"
        ]);

        if ($validated["answering_user_id"] !== $request->user_a_id && $validated["answering_user_id"] !== $request->user_b_id) {
            return response()->json([
                "error" => "Nem válaszolhatsz erre a kérdésre.",
            ], 403);
        }

        $session = GameMatch::with('questions')
            ->byUuid($request->match_uuid)
            ->where('user_id', $validated['answering_user_id'])
            ->first();

        $question = $session->questions
            ->firstWhere(
                'question_id',
                $request->question_id
            );

        $alreadyAnswered = $question->user_answer_id != NULL;

        if ($alreadyAnswered) {
            return response()->json([
                "error" => "Már válaszoltál erre a kérdésre."
            ], 409);
        }

        $isLastQuestion = $question->round_number == config("app.max_rounds");

        $question->update([
            'user_answer_id' => $answer->id,
            'user_guess_time_ms' => 1, // placeholder
        ]);

        return response()->json([
            'success' => true,
            'is_correct' => $answer->is_correct == 1,
            'correct_answer_id' => $question->correct_answer_id,
            'is_last_question' => $isLastQuestion
        ], 200);
    }
}
