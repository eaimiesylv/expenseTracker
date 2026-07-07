<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_splits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')->constrained('expenses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('amount_owed')->default(0);
            $table->unsignedBigInteger('paid_amount')->default(0);
            $table->unsignedBigInteger('outstanding_balance')->default(0);
            $table->date('due_date')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['expense_id', 'payment_status']);
            $table->index(['user_id', 'payment_status']);
            $table->index(['due_date', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_splits');
    }
};
