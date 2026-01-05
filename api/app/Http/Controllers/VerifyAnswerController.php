<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyAnswerRequest;
use App\Models\Answer;
use App\Models\PracticeSession;
use App\Models\PracticeSessionQuestion;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Nette\Utils\Json;

class VerifyAnswerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(VerifyAnswerRequest $request, Answer $answer)
    {
        $correctAnswer = Answer::where([["question_id", "=", $answer->question_id], ["is_correct", "=", 1]])->first();

        $sessionQuestion = PracticeSessionQuestion::create([
            'practice_session_id' => $request->session_id,
            'question_id' => $answer->question_id,
            'user_answer_id' => $answer->id,
            'correct_answer_id' => $correctAnswer->id,
            'round_number' => $request->current_round,
            'user_guess_time_ms' => $request->user_guess_time_ms
        ]);

        $awardedXp = -1;

        if ($request->current_round == 5) {
            $practiceSession = PracticeSession::find($request->session_id);

            $currentXp = $request->user()->xp;

            $correctAnswerCount = $practiceSession->correctAnswersCount();

            $awardedXp = $correctAnswerCount * 10;

            $newXp = $currentXp + $awardedXp;

            $practiceSession->update([
                "xp_after" => $newXp,
            ]);

            $user = User::find($request->user()->id);

            $user->update([
                "xp" => $newXp,
            ]);

            // 100 xp egy level up
            // 1 jó kérdés = 10xp
            // 1 solo = 5*10 = 50xp
            // 2 solo => 1 level up
        }

        return response()->json([
            'success' => true,
            'is_correct' => $answer->is_correct,
            'game_ended' => $awardedXp != -1,
            'xp_gained' => $awardedXp != -1 ? $awardedXp : false,
        ], 200);
    }
}
