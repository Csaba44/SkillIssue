<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMatch extends Model
{
    protected $fillable = [
        "user_id",
        "opponent_id",
        "elo_before",
        "xp_before",
        "elo_after",
        "xp_after",
        "match_uuid",
    ];

    public function reports(): HasMany {
        return $this->hasMany(UserReport::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, "user_id");
    }

    public function opponent(): BelongsTo {
        return $this->belongsTo(User::class, "opponent_id");
    }

    public function questions(): HasMany
    {
        return $this->hasMany(MatchQuestion::class, 'match_id');
    }
}
