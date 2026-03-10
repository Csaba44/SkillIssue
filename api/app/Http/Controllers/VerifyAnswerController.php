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
    public function __invoke(VerifyAnswerRequest $request, Answer $answer)
    {
        $pracSessQuestion = PracticeSessionQuestion::where('practice_session_id', $request->session_id)
            ->where('question_id', $answer->question_id)
            ->firstOrFail();

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
            'user_answer_id' => $answer->id,
            'user_guess_time_ms' => $guessTime
        ]);

        $awardedXp = -1;

        if ($pracSessQuestion->round_number == config("app.max_rounds")) {
            $practiceSession = PracticeSession::find($request->session_id);

            $currentXp = $request->user()->xp;

            $correctAnswerCount = $practiceSession->correctAnswersCount();

            error_log($correctAnswerCount);
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
            'correct_answer_id' => $pracSessQuestion->correct_answer_id,
            'game_ended' => $awardedXp != -1,
            'xp_gained' => $awardedXp != -1 ? $awardedXp : false,
        ], 200);
    }
}
