<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
           $table->ulid('id')->primary();

            $table->foreignUlid('budget_category_id')
                ->nullable()
                ->constrained('budget_categories')
                ->nullOnDelete();

            $table->string('name');

            $table->enum('owner_type', [
                'personal',
                'group'
            ]);

            $table->ulid('owner_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
