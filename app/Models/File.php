<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    use HasFactory;


    // app/Models/File.php
    protected $fillable = ['name', 'filename', 'filepath', 'department', 'upload_date', 'deleted', 'category'];

}
