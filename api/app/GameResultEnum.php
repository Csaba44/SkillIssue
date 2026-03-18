<?php

namespace App;

enum GameResultEnum: string
{
    case WIN = 'Win';
    case LOSE = 'Lose';
    case DRAW = 'Draw';
    case CANCELLED = 'Cancelled';
    case PENDING = 'Pending';
}
