<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        $dates = $this->gameMatches->pluck('created_at')
            ->concat($this->practiceSessions->pluck('created_at'))
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
