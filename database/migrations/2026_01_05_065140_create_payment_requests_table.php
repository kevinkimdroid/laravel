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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id');
            $table->string('type'); // 'registration_fee' or 'monthly_contribution'
            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->string('mpesa_code', 10);
            $table->text('mpesa_message')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
