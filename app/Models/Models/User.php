<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'is_trial_used',
        'subscription_status',
        'subscribed_at',
        'expire_at',
        'is_delete',
        'deleted_at',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

}
