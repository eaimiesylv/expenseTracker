<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_pools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('pool_type')->default('personal');
            $table->unsignedBigInteger('fixed_contribution_amount')->default(0);
            $table->foreignId('contribution_frequency_id')->nullable()->constrained('savings_frequencies')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('payout_frequency_id')->nullable()->constrained('savings_frequencies')->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('payout_order')->default(0);
            $table->foreignId('current_payout_member_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('status')->default('active');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['owner_id', 'status']);
            $table->index(['group_id', 'status']);
            $table->index(['pool_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_pools');
    }
};
