<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'income',
        'expense',
        'transaction_date',
    ];

    protected $casts = [
        'income' => 'decimal:2',
        'expense' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
