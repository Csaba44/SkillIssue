<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionReport extends Model
{
    protected $fillable = [
        "user_id",
        "question_id",
        "comment",
        "status"
    ];

    
}
