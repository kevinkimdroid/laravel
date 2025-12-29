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
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('initials')->nullable();
            $table->string('registration')->nullable();

            // Monthly amounts January through December
            $table->decimal('jan', 12, 2)->default(0);
            $table->decimal('feb', 12, 2)->default(0);
            $table->decimal('mar', 12, 2)->default(0);
            $table->decimal('apr', 12, 2)->default(0);
            $table->decimal('may', 12, 2)->default(0);
            $table->decimal('jun', 12, 2)->default(0);
            $table->decimal('jul', 12, 2)->default(0);
            $table->decimal('aug', 12, 2)->default(0);
            $table->decimal('sep', 12, 2)->default(0);
            $table->decimal('oct', 12, 2)->default(0);
            $table->decimal('nov', 12, 2)->default(0);
            $table->decimal('dec', 12, 2)->default(0);

            $table->decimal('deficit', 12, 2)->default(0);
            $table->decimal('expected_amount', 12, 2)->default(0);

            // e.g. aging in days or months
            $table->unsignedInteger('aging')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};
