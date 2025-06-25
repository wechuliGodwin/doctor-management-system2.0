<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchPaper extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'research_papers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'authors',
        'journal',
        'date',
        'link',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime', // Ensures 'date' is treated as a Carbon instance
    ];

    /**
     * Get the year from the date attribute for grouping.
     *
     * @return string|int
     */
    public function getYearAttribute()
    {
        return $this->date ? $this->date->year : 'Unknown';
    }
}