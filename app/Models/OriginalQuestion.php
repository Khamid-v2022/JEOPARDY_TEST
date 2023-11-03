<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginalQuestion extends Model
{
    protected $table = 'original_questions';
    protected $primaryKey = 'id';

    public $timestamps = false;
    
    protected $fillable = [
        'category',
        'value',
        'question',
        'answer'
    ];
    
}
