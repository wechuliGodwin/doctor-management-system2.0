<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'responses'
    ];

    // If you want to cast the responses to an array for easier manipulation
    protected $casts = [
        'responses' => 'array',
    ];
}