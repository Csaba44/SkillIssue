<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatchQuestion extends Model
{
    use SoftDeletes;
    protected $table = 'match_question';

    protected $fillable = [
        'game_match_id',
        'question_id',
        'user_answer_id',
        'correct_answer_id',
        'round_number',
        'user_guess_time_ms',
    ];

    public function gameMatch(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'game_match_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function userAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'user_answer_id');
    }

    public function correctAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'correct_answer_id');
    }
}
