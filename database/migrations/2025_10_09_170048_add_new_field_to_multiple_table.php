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
        Schema::table('tables', function (Blueprint $table) {
            $table->foreignId('area_id')->nullable()->after('business_id')->constrained('areas')->cascadeOnDelete();
            $table->string('qr_code')->nullable()->after('is_booked');
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('companyName');
            $table->unique('slug');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->foreignId('business_id')->nullable()->after('user_id')->constrained('businesses')->cascadeOnDelete();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->foreignId('business_id')->nullable()->after('id')->constrained('businesses')->cascadeOnDelete();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('status')->default(1)->after('menu_id');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->string('image')->nullable()->after('designation');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('business_id')->nullable()->after('id')->constrained('businesses')->cascadeOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->nullable()->after('phone');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->string('order_status')->nullable()->after('status'); //pending,confirmed,processing,delivered
            $table->foreignId('billing_address_id')->nullable()->after('payment_type_id')->constrained('sales')->nullOnDelete();
            $table->foreignId('business_gateway_id')->nullable()->after('billing_address_id')->constrained('business_gateways')->nullOnDelete();
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->string('cooking_status')->default('pending')->after('quantities'); // pending, start, ready
        });

         Schema::table('plans', function (Blueprint $table) {
            $table->integer('addon_domain_limit')->nullable()->after('status');
            $table->integer('subdomain_limit')->nullable()->after('addon_domain_limit');
        });

        Schema::table('plan_subscribes', function (Blueprint $table) {
            $table->integer('addon_domain_limit')->default(0)->after('price');
            $table->integer('subdomain_limit')->default(0)->after('addon_domain_limit');
        });

        Schema::table('kot_tickets', function (Blueprint $table) {
            $table->foreignId('cancel_reason_id')->nullable()->after('table_id')->constrained('cancel_reasons')->nullOnDelete();
            $table->string('kot_number')->nullable()->after('cancel_reason_id');
            $table->string('status')->nullable()->after('bill_no'); //pending, preparing, ready, served, cancelled
            $table->text('notes')->nullable()->after('status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->longText('fcm_token')->nullable()->after('visibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn('area_id');
            $table->dropColumn('qr_code');
        });

        Schema::table('businesses', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('business_id');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('business_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('business_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gender');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('order_status');
            $table->dropColumn('billing_address_id');
            $table->dropColumn('billing_address_id');
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropColumn('is_marked');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['addon_domain_limit', 'subdomain_limit']);
        });

        Schema::table('plan_subscribes', function (Blueprint $table) {
            $table->dropColumn(['addon_domain_limit', 'subdomain_limit']);
        });

        Schema::table('kot_tickets', function (Blueprint $table) {
            $table->dropColumn(['cancel_reason_id', 'kot_number', 'status', 'notes']);
        });

         Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });

    }
};
