<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserAnswer;
use App\Models\FeatureTaskHeader;

class UserAnswerHeader extends Model
{
    protected $table = 'user_answer_headers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'score',
        'number_of_questions',
        'test_type',            // 0: general test, 1: free trial test, 2: featured test
        'featured_test_id',
        'started_at',
        'ended_at'
    ];

    public function get_questions() {
        $questions = UserAnswer::where('header_id', $this->id)->get();

        return $questions;
    }

    public function get_test_time() {
        $from = strtotime($this->started_at);
        $to = strtotime($this->ended_at);

        $diff = abs($to - $from);

        $minutes = floor($diff / 60);
        $seconds = $diff % 60;

        $total_second = $diff;

        $timeDifference = $minutes > 0 ? "{$minutes}min {$seconds}s" : "{$seconds}s";

        return array('formated' => $timeDifference, 'second' => $total_second);
    }

    public function getFeatureTestInfo() {
        if($this->featured_test_id) {
            return FeatureTaskHeader::where('id', $this->featured_test_id)->first();
        }
        return false;
    }
    
}
