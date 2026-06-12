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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('party_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('delivery_addresses')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tax_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('kot_id')->nullable()->constrained('kot_tickets')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_type_id')->nullable()->constrained()->nullOnDelete();
            $table->double('discountAmount', 10, 2)->default(0);
            $table->double("discountPercentage", 10, 2)->default(0);
            $table->string("discount_type")->nullable(); //flat,percentage
            $table->double("tax_amount", 10, 2)->default(0);
            $table->double('dueAmount', 10, 2)->default(0);
            $table->double('paidAmount', 10, 2)->default(0);
            $table->double('totalAmount', 10, 2)->default(0);
            $table->string('invoiceNumber')->nullable();
            $table->string('sales_type'); //pick_up,delivery,dine_in
            $table->timestamp('saleDate')->nullable();
            $table->string('status')->nullable();
            $table->longText('sale_data')->nullable();
            $table->text('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
