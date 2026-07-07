<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_budget_id')->constrained('budgets')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('personal_budget_id')->constrained('budgets')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('share_amount')->default(0);
            $table->unsignedSmallInteger('share_percentage')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['group_budget_id', 'personal_budget_id']);
            $table->index(['personal_budget_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_links');
    }
};
