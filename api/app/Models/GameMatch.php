<?php

namespace App\Models;

use App\GameResultEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameMatch extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "user_id",
        "opponent_id",
        "elo_before",
        "xp_before",
        "elo_after",
        "xp_after",
        "match_result",
        "match_uuid",
    ];

    protected $casts = [
        'match_result' => GameResultEnum::class
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(UserReport::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function opponent(): BelongsTo
    {
        return $this->belongsTo(User::class, "opponent_id");
    }

    public function questions(): HasMany
    {
        return $this->hasMany(MatchQuestion::class, 'game_match_id');
    }

    public static function findPairByUuid(string $uuid)
    {
        $matches = self::where('match_uuid', $uuid)->get();

        if ($matches->count() !== 2) {
            throw new \RuntimeException("Invalid match pair for uuid: {$uuid}");
        }

        return $matches;
    }

    public function scopeByUuid($query, string $uuid)
    {
        return $query->where('match_uuid', $uuid);
    }
}
