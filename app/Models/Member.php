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

    /**
     * Get the member's join date (first MONTHLY contribution date)
     * This is the earliest MONTHLY contribution date (excludes registration fees)
     * Registration fees don't count as join date - only monthly contributions do
     * 
     * @return \Carbon\Carbon|null
     */
    public function getJoinDate()
    {
        // Only look at monthly contributions, not registration fees
        $firstMonthlyContribution = $this->contributions()
            ->where('type', 'monthly_contribution')
            ->orderBy('contribution_date', 'asc')
            ->first();
        
        if ($firstMonthlyContribution) {
            return \Carbon\Carbon::parse($firstMonthlyContribution->contribution_date)
                ->startOfMonth(); // Start of the month they joined
        }
        
        // If no monthly contributions, check if they have any contributions at all
        // If they only have registration fee, use that date but they haven't really "joined" yet
        $anyContribution = $this->contributions()
            ->orderBy('contribution_date', 'asc')
            ->first();
        
        if ($anyContribution) {
            // They have a registration fee but no monthly contributions yet
            // Return the registration fee date, but they're not considered "joined" for calculations
            return \Carbon\Carbon::parse($anyContribution->contribution_date)
                ->startOfMonth();
        }
        
        // If no contributions at all, default to July 2024 (club start date)
        return \Carbon\Carbon::parse('2024-07-01');
    }
}
