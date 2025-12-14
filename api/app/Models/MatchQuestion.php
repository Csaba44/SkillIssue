<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchQuestion extends Model
{
    protected $table = 'match_question';

    protected $fillable = [
        'match_id',
        'question_id',
        'user_answer_id',
        'correct_answer_id',
        'round_number',
        'user_guess_time_ms',
    ];

    public function gameMatch(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
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
