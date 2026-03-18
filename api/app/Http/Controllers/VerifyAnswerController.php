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

use function Laravel\Prompts\error;

class VerifyAnswerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(VerifyAnswerRequest $request, string $answer)
    {
        $isNull = $answer === "null";
        $answerModel = $isNull ? null : Answer::findOrFail($answer);

        $pracSessQuestion = PracticeSessionQuestion::where('practice_session_id', $request->session_id)
            ->where('question_id', $isNull ? $request->question_id : $answerModel->question_id)
            ->firstOrFail();

        if (!$isNull) {
            $alreadyAnswered = $pracSessQuestion->user_answer_id != NULL;

            if ($alreadyAnswered) {
                return response()->json([
                    "error" => "Már válaszoltál erre a kérdésre."
                ], 409);
            }

            $createdAtTime = $pracSessQuestion->created_at;
            $now = now();
            $guessTime = abs($now->diffInMilliseconds($createdAtTime));

            $pracSessQuestion->update([
                'user_answer_id' => $answerModel->id,
                'user_guess_time_ms' => $guessTime
            ]);
        }

        $awardedXp = -1;

        if ($pracSessQuestion->round_number == config("app.max_rounds")) {
            $practiceSession = PracticeSession::find($request->session_id);

            $currentXp = $request->user()->xp;
            $correctAnswerCount = $practiceSession->correctAnswersCount();

            error_log($correctAnswerCount);
            $awardedXp = $correctAnswerCount * 10;
            $newXp = $currentXp + $awardedXp;

            $practiceSession->update(["xp_after" => $newXp]);

            User::find($request->user()->id)->update(["xp" => $newXp]);
        }

        return response()->json([
            'success' => true,
            'practice_session_id' => $pracSessQuestion->practiceSession->id,
            'is_correct' => $isNull ? false : $answerModel->is_correct,
            'correct_answer_id' => $pracSessQuestion->correct_answer_id,
            'game_ended' => $awardedXp != -1,
            'xp_gained' => $awardedXp != -1 ? $awardedXp : false,
        ], 200);
    }
}
