<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OriginalQuestion;

class UserAnswer extends Model
{
    protected $table = 'user_answers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'header_id',
        'user_id',
        'question_id',
        'question',
        'answer',
        'value',
        'user_answer',
        'answer_time',
        'is_correct'
    ];
    
    public function get_question() {
        $question = OriginalQuestion::where('id', $this->question_id)->first();

        return $question;
    }
}
