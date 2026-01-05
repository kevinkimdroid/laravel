<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'type',
        'amount',
        'payment_date',
        'mpesa_code',
        'mpesa_message',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationship: PaymentRequest belongs to a Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relationship: PaymentRequest approved by a User (admin)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
