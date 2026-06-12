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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('party_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('delivery_addresses')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tax_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_type_id')->nullable()->constrained()->nullOnDelete();
            $table->double('coupon_amount')->default(0);
            $table->double('coupon_percentage')->default(0);
            $table->double('discountAmount')->default(0);
            $table->double("discountPercentage")->default(0);
            $table->string("discount_type")->nullable(); //flat,percentage
            $table->double("tax_amount")->default(0);
            $table->double('totalAmount')->default(0);
            $table->double('dueAmount')->default(0);
            $table->double('paidAmount')->default(0);
            $table->string('invoiceNumber')->nullable();
            $table->timestamp('quotationDate')->nullable();
            $table->longText('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
