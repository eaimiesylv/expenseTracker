<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_expense_id')->constrained('expenses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('personal_expense_id')->constrained('expenses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('share_amount')->default(0);
            $table->unsignedSmallInteger('share_percentage')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['group_expense_id', 'personal_expense_id']);
            $table->index(['personal_expense_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_links');
    }
};
