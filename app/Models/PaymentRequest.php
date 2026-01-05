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
        'checkout_request_id',
        'merchant_request_id',
        'result_code',
        'result_desc',
        'mpesa_receipt_number',
        'transaction_date',
        'amount_paid',
        'phone_number',
        'callback_data',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'approved_at' => 'datetime',
        'transaction_date' => 'datetime',
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
