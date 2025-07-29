<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\BookingResetPasswordNotification;

class BookingUserAuth extends Authenticatable
{
    use Notifiable;

    protected $table = 'booking_user_auth';

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role',
        'hospital_branch',
        'switchable_branches',
        'branch_permissions',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'hospital_branch' => 'string',
        'switchable_branches' => 'array',
        'branch_permissions' => 'array',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new BookingResetPasswordNotification($token));
    }
}