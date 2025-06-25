<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReminderLog extends Model
{
    // Allow mass assignment for the 'sent_at' column
    protected $fillable = ['sent_at'];
}
