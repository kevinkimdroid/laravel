<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QplGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_date',
        'home_team',
        'away_team',
        'home_score',
        'away_score',
        'venue',
        'notes',
    ];

    protected $casts = [
        'game_date' => 'date',
        'home_score' => 'integer',
        'away_score' => 'integer',
    ];
}
