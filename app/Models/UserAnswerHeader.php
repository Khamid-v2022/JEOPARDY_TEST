<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswerHeader extends Model
{
    protected $table = 'user_answer_headers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'score',
        'is_trial_test'
    ];
    
}
