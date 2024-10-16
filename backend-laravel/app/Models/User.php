<?php

namespace App\Models;

use App\Notifications\MailResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends \Illuminate\Foundation\Auth\User
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'status',
        'roles',
        'activation_token',
        'avatar',
        'city',
        'code_postal',
        'phone',
        'address',
        'sexe',
        'stripe_customer',
        'date_of_birth'
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

    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_PROF = "ROLE_TUTOR";
    const ROLE_ETUDIANT = "ROLE_STUDENT";

    /**
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    /**
     * Relation with one element of the table banking_information
     *
     * @return HasOne
     */
    public function bankingInformation(): HasOne
    {
        return $this->hasOne(BankingInformation::class, 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->status;
    }
}
