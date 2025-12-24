<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    protected $table = 'contributions';

    protected $fillable = [
        'member_id',
        'amount',
        'contribution_date'
    ];

    protected $casts = [
        'contribution_date' => 'date',
        'amount' => 'decimal:2'
    ];

    /**
     * Relationship: Contribution belongs to a Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
