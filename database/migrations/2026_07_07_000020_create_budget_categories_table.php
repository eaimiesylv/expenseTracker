<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained('budgets')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('expense_category_id')->nullable()->constrained('expense_categories')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->unsignedBigInteger('budget_amount')->default(0);
            $table->unsignedBigInteger('actual_spending')->default(0);
            $table->boolean('allow_rollover')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['budget_id', 'name']);
            $table->index(['expense_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_categories');
    }
};
