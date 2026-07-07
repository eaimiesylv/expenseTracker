<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_pool_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pool_id')->constrained('savings_pools')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('membership_status')->default('active');
            $table->unsignedInteger('payout_order')->default(0);
            $table->date('joined_at');
            $table->date('left_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pool_id', 'user_id']);
            $table->index(['pool_id', 'membership_status']);
            $table->index(['user_id', 'membership_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_pool_members');
    }
};
