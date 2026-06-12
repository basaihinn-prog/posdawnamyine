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
        // BUSINESS TABLE
        Schema::table('businesses', function (Blueprint $table) {
            $table->boolean('status')->default(1)->after('vat_no');
        });

        Schema::table('tables', function (Blueprint $table) {
            $table->boolean('status')->default(1)->after('is_booked');
        });

        Schema::table('kot_tickets', function (Blueprint $table) {
            $table->dropUnique(['bill_no']);

            $table->string('bill_no')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('business_id')->constrained('users')->nullOnDelete();
        });

        Schema::table('parties', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('business_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         // BUSINESS TABLE
         Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('status');
         });

         Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn('status');
         });

         Schema::table('kot_tickets', function (Blueprint $table) {
            $table->string('bill_no')->unique()->nullable()->change();
         });

         Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('user_id');
         });

         Schema::table('parties', function (Blueprint $table) {
            $table->dropColumn('user_id');
         });
    }
};
