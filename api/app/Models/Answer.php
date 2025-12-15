<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Answer extends Model
{
    protected $fillable = [
        "question_id",
        "answer",
        "is_correct"
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    // Sessions where this answer was chosen by user
    public function practiceSessionsAsUserAnswer(): BelongsToMany
    {
        return $this->belongsToMany(
            PracticeSession::class,
            'practice_session_question',
            'user_answer_id',
            'practice_session_id'
        )->withPivot('question_id', 'correct_answer_id', 'round_number', 'user_guess_time_ms');
    }

    // Sessions where this answer was the correct one
    public function practiceSessionsAsCorrectAnswer(): BelongsToMany
    {
        return $this->belongsToMany(
            PracticeSession::class,
            'practice_session_question',
            'correct_answer_id',
            'practice_session_id'
        )->withPivot('question_id', 'user_answer_id', 'round_number', 'user_guess_time_ms');
    }
}
