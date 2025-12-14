<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PracticeSession extends Model
{
    protected $fillable = [
        "user_id",
        "rounds",
        "xp_before",
        "xp_after"
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Questions in the session (via pivot)
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'practice_session_question')
            ->withPivot('user_answer_id', 'correct_answer_id', 'round_number', 'user_guess_time_ms');
    }

    // Answers the user actually picked (via pivot.user_answer_id)
    public function userAnswers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'practice_session_question', 'practice_session_id', 'user_answer_id')
            ->withPivot('question_id', 'correct_answer_id', 'round_number', 'user_guess_time_ms');
    }

    // Correct answers for those questions (via pivot.correct_answer_id)
    public function correctAnswers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'practice_session_question', 'practice_session_id', 'correct_answer_id')
            ->withPivot('question_id', 'user_answer_id', 'round_number', 'user_guess_time_ms');
    }

    public function sessionQuestions(): HasMany
    {
        return $this->hasMany(PracticeSessionQuestion::class);
    }
}
