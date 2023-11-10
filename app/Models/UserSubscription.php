<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $table = 'user_subscriptions';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id',
        'plan_id',
        'stripe_customer_id',
        'stripe_plan_price_id',
        'stripe_payment_intent_id',
        'stripe_subscription_id',
        'default_payment_method',
        'default_source',
        'paid_amount',
        'paid_amount_currency',
        'plan_interval',
        'plan_interval_count',
        'customer_name',
        'customer_email',
        'plan_period_start',
        'plan_period_end',
        'status'
    ];
    
}
