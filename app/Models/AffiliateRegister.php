<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateRegister extends Model
{
    protected $table = 'affiliate_registers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'refer_user_id',
        'refer_string',
        'user_id',
    ];
}
