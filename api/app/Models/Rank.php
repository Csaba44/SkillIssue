<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    protected $fillable = [
        'name',
        'min_elo'
    ];


    public function users()
    {
        $nextRankMinElo = Rank::where('min_elo', '>', $this->min_elo)
            ->orderBy('min_elo', 'asc')
            ->value('min_elo');

        return User::where('elo', '>=', $this->min_elo)
            ->when($nextRankMinElo, function ($query) use ($nextRankMinElo) {
                $query->where('elo', '<', $nextRankMinElo);
            })
            ->get();
    }
}
