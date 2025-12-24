<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_no',
        'name',
        'phone',
        'status'
    ];

    /**
     * Relationship: Member has many Contributions
     */
    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'member_id', 'id');
    }
}
