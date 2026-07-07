<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pool_id')->constrained('savings_pools')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('cycle_number')->default(1);
            $table->date('cycle_start');
            $table->date('cycle_end');
            $table->dateTime('payout_date')->nullable();
            $table->dateTime('next_payout_at')->nullable();
            $table->dateTime('cron_processed_at')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pool_id', 'cycle_number']);
            $table->index(['pool_id', 'status']);
            $table->index(['next_payout_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_cycles');
    }
};
