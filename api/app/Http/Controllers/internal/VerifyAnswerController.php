<?php

namespace App\Http\Controllers\internal;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\GameMatch;
use App\Models\MatchQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseFactoryInterface;

class VerifyAnswerController extends Controller
{
    public function __invoke(Request $request, Answer $answer)
    {
        $MAX_ROUNDS = config("app.max_rounds");

        $validated = $request->validate([
            "answering_user_id" => "required|integer|exists:users,id"
        ]);

        $this->authorizeUser($validated['answering_user_id'], $request);

        $session = $this->getUserSession($request->match_uuid, $validated['answering_user_id']);

        $question = $this->getMatchQuestion($session, $request->question_id);

        $this->validateAnswerBelongsToQuestion($answer, $question);
        $this->ensureQuestionNotExpired($question);

        if ($question->user_answer_id !== null) {
            return response()->json(["error" => "Már válaszoltál erre a kérdésre."], 409);
        }

        $question->update([
            'user_answer_id' => $answer->id,
            'user_guess_time_ms' => 1,
        ]);

        $finishedForUser = $this->isUserFinished($session->id);
        $matches = GameMatch::findPairByUuid($request->match_uuid);
        $allFinished = $this->areAllUsersFinished($matches);

        $response = [
            'success' => true,
            'is_correct' => $answer->is_correct == 1,
            'correct_answer_id' => $question->correct_answer_id,
            'finished_for_user' => $finishedForUser,
        ];

        if ($finishedForUser) {
            $response['user_correct_answers'] = $this->calculateScore($session->id);
            $response['questions_count'] = $MAX_ROUNDS;
        }

        if ($allFinished) {
            $result = $this->determineMatchResult($matches);

            $response['game_finished'] = true;
            $response['scores'] = $result['scores'];
            $response['winner_id'] = $result['winner_id'];
        }

        return response()->json($response);
    }

    private function authorizeUser(int $userId, Request $request): void
    {
        if ($userId !== $request->user_a_id && $userId !== $request->user_b_id) {
            abort(403, "Nem válaszolhatsz erre a kérdésre.");
        }
    }

    private function getUserSession(string $uuid, int $userId): GameMatch
    {
        return GameMatch::with('questions')
            ->byUuid($uuid)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    private function getMatchQuestion(GameMatch $session, int $questionId): MatchQuestion
    {
        $question = $session->questions->firstWhere('question_id', $questionId);

        if (!$question) {
            abort(404, "Érvénytelen kérdés.");
        }

        return $question;
    }

    private function validateAnswerBelongsToQuestion(Answer $answer, MatchQuestion $question): void
    {
        if ($answer->question_id !== $question->question_id) {
            abort(422, "Ez a válasz nem ehhez a kérdéshez tartozik.");
        }
    }

    private function isUserFinished(int $gameMatchId): bool
    {
        return MatchQuestion::where('game_match_id', $gameMatchId)
            ->where(function ($q) {
                $q->whereNull('user_answer_id')
                    ->where('round_expires_at', '>', now());
            })
            ->doesntExist();
    }

    private function areAllUsersFinished($matches): bool
    {
        return $matches->every(function ($match) {
            return $this->isUserFinished($match->id);
        });
    }

    private function calculateScore(int $gameMatchId): int
    {
        return MatchQuestion::where('game_match_id', $gameMatchId)
            ->whereColumn('user_answer_id', 'correct_answer_id')
            ->count();
    }

    private function determineMatchResult($matches): array
    {
        $scores = [];

        foreach ($matches as $match) {
            $scores[$match->user_id] = $this->calculateScore($match->id);
        }

        $userIds = array_keys($scores);

        if ($scores[$userIds[0]] > $scores[$userIds[1]]) {
            $winnerId = $userIds[0];
        } elseif ($scores[$userIds[1]] > $scores[$userIds[0]]) {
            $winnerId = $userIds[1];
        } else {
            $winnerId = null;
        }

        return [
            'scores' => $scores,
            'winner_id' => $winnerId
        ];
    }

    private function ensureQuestionNotExpired(MatchQuestion $question): void
    {
        if ($question->round_expires_at <= now()) {
            abort(410, "A kérdés ideje lejárt.");
        }
    }
}
