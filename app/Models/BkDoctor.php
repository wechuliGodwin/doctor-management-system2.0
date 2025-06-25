<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkDoctor extends Model
{
    use HasFactory;

    protected $table = 'bk_doctor';

    // Fields that can be mass-assigned
    protected $fillable = [
        'group_id',
        'doctor_name',
        'phone',
        'email',
        'hospital_branch',
        'department'
    ];

    // Disable timestamps if your table doesn't have created_at / updated_at
    public $timestamps = false;
}
