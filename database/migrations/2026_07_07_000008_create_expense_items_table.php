<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('default_expense_category_id')->nullable()->constrained('expense_categories')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('usage_count')->default(0);
            $table->unsignedInteger('popularity_score')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'slug']);
            $table->index(['name']);
            $table->index(['usage_count', 'popularity_score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_items');
    }
};
