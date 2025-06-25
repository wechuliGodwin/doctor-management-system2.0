<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telepharmacy extends Model
{
    protected $fillable = [
        'user_id', 'type', 'drug_name', 'quantity', 'prescription_path', 'delivery_address',
        'consultation_date', 'consultation_type', 'notes', 'original_order_id', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function originalOrder()
    {
        return $this->belongsTo(Telepharmacy::class, 'original_order_id');
    }
}

