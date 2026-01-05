<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'activity_date',
        'start_time',
        'end_time',
        'venue',
        'type',
        'status',
        'notes',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
