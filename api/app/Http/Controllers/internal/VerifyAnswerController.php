<?php

namespace App\Http\Controllers\internal;

use App\GameResultEnum;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\GameMatch;
use App\Models\MatchQuestion;
use App\Models\User;
use Illuminate\Http\Request;

class VerifyAnswerController extends Controller
{
    public function __invoke(Request $request, Answer $answer)
    {
        $MAX_ROUNDS = config("app.max_rounds");

        $validated = $request->validate([
            "answering_user_id" => "required|integer|exists:users,id"
        ]);

        $userA = User::findOrFail($request->user_a_id);
        $userB = User::findOrFail($request->user_b_id);

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
        $allFinished = $this->isGameFinished($matches, $MAX_ROUNDS);

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
            $result = $this->determineMatchResult($matches, $userA, $userB);

            $response['game_finished'] = true;
            $response['scores'] = $result['scores'];
            $response['winner_id'] = $result['winner_id'];
            $response['elo_changes'] = $result['elo_changes'];
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


    private function determineMatchResult($matches, User $userA, User $userB): array
    {
        $scores = [];

        foreach ($matches as $match) {
            $scores[$match->user_id] = $this->calculateScore($match->id);
        }

        $scoreA = $scores[$userA->id];
        $scoreB = $scores[$userB->id];

        if ($scoreA > $scoreB) {
            $winnerId = $userA->id;
            $scoreResultA = 1;
        } elseif ($scoreB > $scoreA) {
            $winnerId = $userB->id;
            $scoreResultA = 0;
        } else {
            $winnerId = null;
            $scoreResultA = 0.5;
        }

        $matchA = $matches->firstWhere('user_id', $userA->id);
        $matchB = $matches->firstWhere('user_id', $userB->id);

        $expectedA = $matchA->expected_winrate;
        $expectedB = $matchB->expected_winrate;

        $eloChangeA = (int) round(30 * ($scoreResultA - $expectedA));
        $eloChangeB = (int) round(30 * ((1 - $scoreResultA) - $expectedB));

        if ($winnerId !== null) {
            if ($eloChangeA > 0) $eloChangeA = max(10, $eloChangeA);
            if ($eloChangeB > 0) $eloChangeB = max(10, $eloChangeB);
        }

        $userA->elo += $eloChangeA;
        $userB->elo += $eloChangeB;
        $userA->xp += $scoreA * 10;
        $userB->xp += $scoreB * 10;

        $userA->save();
        $userB->save();

        foreach ($matches as $match) {
            $isUserA = $match->user_id === $userA->id;

            $userId     = $isUserA ? $userA->id : $userB->id;
            $eloChange  = $isUserA ? $eloChangeA : $eloChangeB;
            $newElo     = $isUserA ? $userA->elo : $userB->elo;
            $newXp      = $isUserA ? $userA->xp  : $userB->xp;

            if ($winnerId === null) {
                $result = GameResultEnum::DRAW;
            } elseif ($winnerId === $userId) {
                $result = GameResultEnum::WIN;
            } else {
                $result = GameResultEnum::LOSE;
            }

            $match->update([
                'match_result' => $result,
                'elo_after'    => $newElo,
                'xp_after'     => $newXp,
            ]);
        }

        return [
            'scores'      => $scores,
            'winner_id'   => $winnerId,
            'elo_changes' => [
                $userA->id => $eloChangeA,
                $userB->id => $eloChangeB,
            ],
        ];
    }

    private function ensureQuestionNotExpired(MatchQuestion $question): void
    {
        if ($question->round_expires_at <= now()) {
            abort(410, "A kérdés ideje lejárt.");
        }
    }

    private function calculateEloChange(int $ratingA, int $ratingB, float $scoreA, int $k = 30): int
    {
        $expectedA = 1 / (1 + pow(10, ($ratingB - $ratingA) / 400));

        $change = $k * ($scoreA - $expectedA);

        return (int) round($change);
    }

    private function isGameFinished($matches, int $maxRounds): bool
    {
        foreach ($matches as $match) {

            $lastRound = MatchQuestion::where('game_match_id', $match->id)
                ->max('round_number');

            if ($lastRound < $maxRounds) {
                return false;
            }

            $unanswered = MatchQuestion::where('game_match_id', $match->id)
                ->where('round_number', $maxRounds)
                ->whereNull('user_answer_id')
                ->exists();

            if ($unanswered) {
                return false;
            }
        }

        return true;
    }
}
