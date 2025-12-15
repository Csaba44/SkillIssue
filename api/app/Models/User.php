<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'streak_count',
        'elo',
        'xp',
        'is_admin',
        'last_login_ip_hash',
        'last_login'
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

    public function reportsSent(): HasMany {
        return $this->hasMany(UserReport::class);
    }

    public function practiceSessions(): HasMany {
        return $this->hasMany(PracticeSession::class);
    }

    public function questionReports(): HasMany {
        return $this->HasMany(QuestionReport::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'last_login_ip_hash',
        'last_login'
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
