<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierAuth extends Model
{
    protected $fillable = ['email', 'payroll_id'];
    // Include any other fields in $fillable if necessary
}