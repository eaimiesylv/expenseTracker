<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->nullable()->constrained('budgets')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('recurrence_rule_id')->constrained('recurrence_rules')->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('next_occurrence_at')->nullable();
            $table->dateTime('last_generated_at')->nullable();
            $table->boolean('auto_generate')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'next_occurrence_at']);
            $table->index(['budget_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_budgets');
    }
};
