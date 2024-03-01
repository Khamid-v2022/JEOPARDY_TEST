<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\FeatureTaskQuestion;

class FeatureTaskHeader extends Model
{
    protected $table = 'feature_task_header';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'thumbnail',
        'title'
    ];
    
    public function get_question() {
        $question = FeatureTaskQuestion::where('header_id', $this->id)->get();

        return $question;
    }
}
