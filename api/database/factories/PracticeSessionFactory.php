<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PracticeSession extends Model
{
    use HasFactory;
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

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'practice_session_question')
            ->withPivot('user_answer_id', 'correct_answer_id', 'round_number', 'user_guess_time_ms');
    }

    public function userAnswers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'practice_session_question', 'practice_session_id', 'user_answer_id')
            ->withPivot('question_id', 'correct_answer_id', 'round_number', 'user_guess_time_ms');
    }

    public function correctAnswers(): BelongsToMany
    {
        return $this->belongsToMany(Answer::class, 'practice_session_question', 'practice_session_id', 'correct_answer_id')
            ->withPivot('question_id', 'user_answer_id', 'round_number', 'user_guess_time_ms');
    }

    public function correctAnswersCount(): int
    {
        return $this->sessionQuestions->filter(function ($sq) {
            return $sq->user_answer_id == $sq->correct_answer_id;
        })->count();
    }


    public function sessionQuestions(): HasMany
    {
        return $this->hasMany(PracticeSessionQuestion::class);
    }
}
