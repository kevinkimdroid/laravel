<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            // Make mpesa_code nullable for STK Push payments
            $table->string('mpesa_code', 10)->nullable()->change();
            
            // Add 'processing' status
            $table->enum('status', ['pending', 'approved', 'rejected', 'processing'])->default('pending')->change();
            
            // Daraja API fields
            $table->string('checkout_request_id')->nullable()->unique()->after('mpesa_code');
            $table->string('merchant_request_id')->nullable()->after('checkout_request_id');
            $table->string('result_code')->nullable()->after('merchant_request_id');
            $table->text('result_desc')->nullable()->after('result_code');
            $table->string('mpesa_receipt_number')->nullable()->after('result_desc');
            $table->timestamp('transaction_date')->nullable()->after('mpesa_receipt_number');
            $table->decimal('amount_paid', 12, 2)->nullable()->after('transaction_date');
            $table->string('phone_number')->nullable()->after('amount_paid');
            $table->text('callback_data')->nullable()->after('phone_number'); // Store full callback JSON
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->dropColumn([
                'checkout_request_id',
                'merchant_request_id',
                'result_code',
                'result_desc',
                'mpesa_receipt_number',
                'transaction_date',
                'amount_paid',
                'phone_number',
                'callback_data'
            ]);
            
            // Revert status enum
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
            
            // Revert mpesa_code to not nullable
            $table->string('mpesa_code', 10)->nullable(false)->change();
        });
    }
};
