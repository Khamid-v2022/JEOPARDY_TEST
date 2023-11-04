<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'category_id',
        'question',
        'answer',
        'value'
    ];
    
}
