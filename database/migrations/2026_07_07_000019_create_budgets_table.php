<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete()->cascadeOnUpdate();
            $table->string('scope')->default('personal');
            $table->string('period_type')->default('monthly');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('total_budget')->default(0);
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['owner_id', 'scope']);
            $table->index(['group_id', 'scope']);
            $table->index(['period_type', 'start_date', 'end_date']);
            $table->index(['is_active', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
