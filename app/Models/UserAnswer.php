<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $table = 'user_answers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'header_id',
        'user_id',
        'question_id',
        'user_answer',
        'is_correct'
    ];
    
}
