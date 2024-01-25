<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'avatar',
        'email',
        'is_email_verified',
        'verify_code',
        'email_verified_at',
        'address',
        'city',
        'zipcode',
        'country',
        'default_question_count',
        'password',
        'is_trial_used',
        'subscription_status',
        'subscription_plan',
        'subscribed_at',
        'expire_at',
        'is_delete',
        'deleted_at',
        'remember_token',
        'last_login_at',
        'last_reminder_emailed_at',
        'last_tested_at',
        'lognest_streak_days',
        'lognest_streak_started_at',
        'lognest_streak_ended_at',
        'referral_user_count',
        'used_referrals_for_test'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function get_test_count() {
        $tests = UserAnswerHeader::where('user_id', $this->id)->whereNotNull('ended_at')->get();

        return $tests;
    }
}
