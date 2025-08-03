<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkDoctor extends Model
{
    use HasFactory;

    protected $table = 'bk_doctor';

    protected $fillable = [
        'group_id',
        'doctor_name',
        'phone',
        'email',
        'hospital_branch',
        'department'
    ];

    public $timestamps = false;
}
