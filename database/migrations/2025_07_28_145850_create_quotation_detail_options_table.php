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
        Schema::create('quotation_detail_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_detail_id')->constrained()->cascadeOnDelete();
            $table->foreignId('option_id')->nullable()->constrained('modifier_group_options')->cascadeOnDelete();
            $table->foreignId('modifier_id')->nullable()->constrained('modifiers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_detail_options');
    }
};
