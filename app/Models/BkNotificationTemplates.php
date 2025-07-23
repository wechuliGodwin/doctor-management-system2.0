<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkNotificationTemplates extends Model
{
    use HasFactory;

    protected $table = 'bk_notification_templates';

    protected $fillable = [
        'name',
        'type',
        'content',
        'hospital_branch',
        'created_by',
        'updated_by'
    ];
}