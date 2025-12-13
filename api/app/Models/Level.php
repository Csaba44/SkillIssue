<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'level',
        'min_xp',
    ];


    public function users()
    {
        $nextLevelMinXp = Rank::where('min_xp', '>', $this->min_xp)->orderBy('min_xp', 'asc')->value('min_xp');

        return User::where('xp', '>=', $this->min_elo)->when($nextLevelMinXp, function ($query) use ($nextLevelMinXp) {
            $query->where('xp', '<', $nextLevelMinXp);
        })->get();
    }
}
