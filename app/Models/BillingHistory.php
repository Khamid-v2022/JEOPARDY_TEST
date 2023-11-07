<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingHistory extends Model
{
    protected $table = 'billing_histories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'card_number',
        'method',
        'package',
        'amount',
        'period',
        'reference',
        'transaction_id',
        'status',
        'detail_name',
        'detail_address',
        'detail_city',
        'detail_zipcode',
        'detail_country'
    ];
}
