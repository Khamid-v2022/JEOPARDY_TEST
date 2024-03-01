<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureTaskQuestion extends Model
{
    protected $table = 'feature_task_questions';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'header_id',
        'category',
        'question',
        'answer'
    ];
    
}
