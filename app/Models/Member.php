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
        'initials',
        'registration_amount_paid',
        'registration_fee',
        'paid_to_date',
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

    /**
     * Relationship: Member has many Users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'member_id', 'id');
    }
}
