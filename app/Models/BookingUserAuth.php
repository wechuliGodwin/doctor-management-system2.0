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
        'is_active' => 'boolean',
    ];

    // Ensure hospital_branch is always included in switchable_branches
    public function getSwitchableBranchesAttribute($value)
    {
        $branches = is_array($value) ? $value : (is_string($value) ? json_decode($value, true) : []);
        if (!is_array($branches)) {
            $branches = is_string($value) ? [$value] : [];
        }
        if (!in_array($this->hospital_branch, $branches)) {
            $branches[] = $this->hospital_branch;
        }
        return array_unique($branches);
    }

    // Ensure branch_permissions includes hospital_branch with at least read-write
    public function getBranchPermissionsAttribute($value)
    {
        $permissions = is_array($value) ? $value : (is_string($value) ? json_decode($value, true) : []);
        if (!is_array($permissions)) {
            $permissions = [];
        }
        if (!isset($permissions[$this->hospital_branch])) {
            $permissions[$this->hospital_branch] = 'read-write';
        }
        return $permissions;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new BookingResetPasswordNotification($token));
    }
}