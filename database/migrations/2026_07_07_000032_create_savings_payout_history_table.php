<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_payout_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pool_id')->constrained('savings_pools')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('cycle_id')->constrained('savings_cycles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('amount')->default(0);
            $table->dateTime('payout_date');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('status')->default('pending');
            $table->string('transaction_reference')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['pool_id', 'status']);
            $table->index(['recipient_id', 'payout_date']);
            $table->index(['cycle_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_payout_history');
    }
};
