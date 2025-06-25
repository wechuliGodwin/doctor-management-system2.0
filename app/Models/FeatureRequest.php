<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureRequest extends Model
{
    protected $fillable = ['feature', 'department', 'other_department', 'importance', 'contact', 'name', 'email', 'phone'];
}