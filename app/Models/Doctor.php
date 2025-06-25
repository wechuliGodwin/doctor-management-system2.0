<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['name', 'specialty', 'is_available'];

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
}
