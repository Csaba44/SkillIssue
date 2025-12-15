<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        "subject_id",
        "question"
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function practiceSessions(): BelongsToMany
    {
        return $this->belongsToMany(
            PracticeSession::class,
            'practice_session_question',
            'question_id',
            'practice_session_id'
        )->withPivot('user_answer_id', 'correct_answer_id', 'round_number', 'user_guess_time_ms');
    }

    public function questionReports(): HasMany {
        return $this->hasMany(QuestionReport::class);
    }
}
