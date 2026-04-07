<?php

namespace App\Http\Controllers\internal;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Models\MatchQuestion;
use App\Models\Question;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SingleQuestionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        /* 
        'match_uuid',
        'user_a_id',
        'user_b_id'
        */

        $maxRounds = config('app.max_rounds');

        $matchUuid = $request->match_uuid;


        $matches = GameMatch::with('questions')
            ->byUuid($matchUuid)
            ->get();

        if ($matches->count() !== 2) {
            throw new \RuntimeException("Invalid match pair for uuid: {$matchUuid}");
        }

        $excludedQuestions = $matches->first()
            ->questions
            ->pluck('question_id');

        $randomQuestion = Question::with(['subject', 'answers'])
            ->whereNotIn('id', $excludedQuestions)
            ->get()
            ->random();

        // Get the correct answer (and make it hidden again)
        $randomQuestion->answers->makeVisible('is_correct');
        $correctAnswerId = $randomQuestion->answers
            ->firstWhere('is_correct', 1)
            ->id;
        $randomQuestion->answers->makeHidden('is_correct');
        $randomQuestion->setRelation('answers', $randomQuestion->answers->shuffle());


        // Get the current round's number
        $lastRound = $matches->first()->questions->max('round_number');

        $currentRound = ($lastRound ?? 0) + 1;


        // Create a question token
        $payload = [
            'question_id' => $randomQuestion->id,
            'user_a_id'  => $request->user_a_id,
            'user_b_id'  => $request->user_b_id,
            'iat'         => time(),
            'exp'         => time() + 60, // seconds
        ];


        $token = JWT::encode(
            $payload,
            config('app.key'),
            'HS256'
        );

        // Get if it's the end of the session
        if ($currentRound > $maxRounds) {
            return response()->json([
                'success' => false,
                'error' => 'A játszma véget ért.',
            ], 410);
        }

        DB::transaction(function () use ($matches, $randomQuestion, $correctAnswerId, $currentRound) {
            MatchQuestion::create([
                "game_match_id" => $matches[0]->id,
                "question_id" => $randomQuestion->id,
                "correct_answer_id" => $correctAnswerId,
                "round_number" => $currentRound,
                "round_expires_at" => Carbon::now()->addSeconds(45)
            ]);

            MatchQuestion::create([
                "game_match_id" => $matches[1]->id,
                "question_id" => $randomQuestion->id,
                "correct_answer_id" => $correctAnswerId,
                "round_number" => $currentRound,
                "round_expires_at" => Carbon::now()->addSeconds(45)
            ]);
        });

        return response()->json([
            'success' => true,
            'current_round' => $currentRound,
            'question' => $randomQuestion,
            'question_token' => $token
        ]);
    }
}
