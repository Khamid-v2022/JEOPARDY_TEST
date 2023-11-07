<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserAnswer;

class UserAnswerHeader extends Model
{
    protected $table = 'user_answer_headers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'score',
        'is_trial_test',
        'started_at',
        'ended_at'
    ];

    public function get_questions() {
        $questions = UserAnswer::where('header_id', $this->id)->get();

        return $questions;
    }
    
}
