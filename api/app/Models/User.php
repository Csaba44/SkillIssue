<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\GameResultEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Ramsey\Uuid\Type\Integer;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'elo',
        'xp',
        'is_admin',
    ];

    protected $appends = [
        'win_rate',
        'played_matches_count',
        'accuracy',
    ];


    public function getWinRateAttribute()
    {
        $validMatches = $this->gameMatches->filter(function ($match) {
            return !in_array($match->match_result, [
                \App\GameResultEnum::PENDING,
                \App\GameResultEnum::CANCELLED
            ]);
        });

        $totalValidMatches = $validMatches->count();

        if ($totalValidMatches === 0) {
            return 0;
        }

        $wins = $validMatches->filter(function ($match) {
            return $match->match_result === \App\GameResultEnum::WIN;
        })->count();

        return round(($wins / $totalValidMatches) * 100, 2);
    }

    public function getAccuracyAttribute()
    {
        $matchAnswers = MatchQuestion::whereIn('game_match_id', $this->gameMatches()->pluck('id'))
            ->select('user_answer_id', 'correct_answer_id')
            ->get();

        $practiceAnswers = PracticeSessionQuestion::whereIn('practice_session_id', $this->practiceSessions()->pluck('id'))
            ->select('user_answer_id', 'correct_answer_id')
            ->get();


        $allAnswers = $matchAnswers->concat($practiceAnswers);
        $totalQuestions = $allAnswers->count();



        if ($totalQuestions === 0) {
            return 0;
        }

        $correctAnswers = $allAnswers->filter(function ($q) {
            return !is_null($q->user_answer_id) && $q->user_answer_id == $q->correct_answer_id;
        })->count();



        return round(($correctAnswers / $totalQuestions) * 100, 2);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, "badge_user");
    }

    public function getRankAttribute()
    {
        return Rank::where('min_elo', '<=', $this->elo)->orderBy('min_elo', 'desc')->first();
    }

    public function getLevelAttribute()
    {
        return Level::where('min_xp', '<=', $this->xp)->orderBy('min_xp', 'desc')->first();
    }

    public function getNextRankAttribute()
    {
        return Rank::where('min_elo', '>', $this->elo)->orderBy('min_elo', 'asc')->first();
    }

    public function getNextLevelAttribute()
    {
        return Level::where('min_xp', '>', $this->xp)->orderBy('min_xp', 'asc')->first();
    }

    public function reportsSent(): HasMany
    {
        return $this->hasMany(UserReport::class);
    }

    public function practiceSessions(): HasMany
    {
        return $this->hasMany(PracticeSession::class);
    }

    public function questionReports(): HasMany
    {
        return $this->HasMany(QuestionReport::class);
    }

    public function gameMatches(): HasMany
    {
        return $this->hasMany(GameMatch::class);
    }

    public function logins(): HasMany
    {
        return $this->hasMany(UserLogin::class);
    }

    public function getStreakAttribute()
    {
        $validMatches = $this->gameMatches->filter(function ($match) {
            return !in_array($match->match_result, [
                GameResultEnum::CANCELLED,
                GameResultEnum::PENDING,
            ]);
        });

        $validPractice = $this->practiceSessions->filter(function ($session) {
            return $session->xp_after !== null;
        });

        $dates = $validMatches->pluck('created_at')
            ->concat($validPractice->pluck('created_at'))
            ->map->format('Y-m-d')
            ->unique();

        $streak = 0;
        $checkDate = now()->format('Y-m-d');

        if (!$dates->contains($checkDate)) {
            $checkDate = now()->subDay()->format('Y-m-d');
        }

        while ($dates->contains($checkDate)) {
            $streak++;
            $checkDate = \Carbon\Carbon::parse($checkDate)->subDay()->format('Y-m-d');
        }

        return $streak;
    }
    public function bans(): HasMany
    {
        return $this->hasMany(Ban::class);
    }

    public function getIsBannedAttribute()
    {
        $ban = $this->bans()
            ->where('release_date', '>', now())
            ->latest('release_date')
            ->first();

        return $ban ?: false;
    }

    public function getPlayedMatchesCountAttribute()
    {
        $validMatches = $this->gameMatches()
            ->whereNotIn('match_result', [
                GameResultEnum::CANCELLED->value,
                GameResultEnum::PENDING->value,
            ])->count();

        $validPractice = $this->practiceSessions()
            ->whereNotNull('xp_after')
            ->count();

        return $validMatches + $validPractice;
    }

    public function getPlayerTopPercentileAttribute()
    {
        $allMatchesCount = $this->played_matches_count;
        $allUsersCount = self::count();

        if ($allUsersCount === 0) {
            return 1;
        }
        $usersCountWithLessMatches = self::with(['gameMatches', 'practiceSessions'])
            ->get()
            ->filter(function ($u) use ($allMatchesCount) {
                $validMatches = $u->gameMatches->filter(function ($match) {
                    return !in_array($match->match_result, [
                        GameResultEnum::CANCELLED,
                        GameResultEnum::PENDING,
                    ]);
                })->count();

                $validPractice = $u->practiceSessions->filter(function ($session) {
                    return $session->xp_after !== null;
                })->count();

                return ($validMatches + $validPractice) < $allMatchesCount;
            })
            ->count();

        $percentile = ($usersCountWithLessMatches / $allUsersCount) * 100;
        $topPercent = 100 - $percentile;

        return (int) ceil($topPercent);
    }

    public function getWinStreakAttribute()
    {
        $matches = $this->gameMatches()
            ->whereNotIn('match_result', [
                GameResultEnum::CANCELLED->value,
                GameResultEnum::PENDING->value,
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        $streak = 0;

        foreach ($matches as $match) {
            if ($match->match_result == GameResultEnum::WIN) {
                $streak++;
            } else {
                break;
            }
        }
        return $streak;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
