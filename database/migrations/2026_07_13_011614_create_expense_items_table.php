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
        Schema::create('expense_items', function (Blueprint $table) {
            $table->ulid('id')->primary();

    $table->foreignUlid('category_id')
        ->nullable()
        ->constrained('expense_categories')
        ->nullOnDelete();

    $table->foreignUlid('owner_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->string('name');

    $table->timestamps();

    $table->unique(['owner_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_items');
    }
};
