<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',    // The original name of the file (changed from 'name' to 'filename')
        'filepath',
        'category',
        'user_id',     // Link the upload to the uploader
    ];

    // Define relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
