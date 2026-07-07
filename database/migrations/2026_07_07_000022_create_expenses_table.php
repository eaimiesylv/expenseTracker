<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payer_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('ledger_id')->nullable()->constrained('ledgers')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('expense_item_id')->constrained('expense_items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('expense_category_id')->nullable()->constrained('expense_categories')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('budget_category_id')->nullable()->constrained('budget_categories')->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('amount')->default(0);
            $table->string('currency_code', 3)->default('NGN');
            $table->date('expense_date');
            $table->string('status')->default('pending');
            $table->string('approval_mode')->default('none');
            $table->string('receipt_path')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['group_id', 'status']);
            $table->index(['ledger_id', 'expense_date']);
            $table->index(['expense_date', 'status']);
            $table->index(['approval_mode', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
