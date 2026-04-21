<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'min_elo'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
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
