<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReport extends Model
{
    use SoftDeletes;

    protected $appends = ['match_details'];

    protected $fillable = [
        "user_id",
        "game_match_id",
        "round_number",
        "comment",
        "status"
    ];

    public function userReporting(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gameMatch(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class);
    }

    public function userReported(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            GameMatch::class,
            'id',
            'id',
            'game_match_id',
            'opponent_id'
        );
    }

    public function getMatchDetailsAttribute()
    {
        return MatchQuestion::where('game_match_id', $this->game_match_id)
            ->where('round_number', $this->round_number)
            ->first();
    }
}
