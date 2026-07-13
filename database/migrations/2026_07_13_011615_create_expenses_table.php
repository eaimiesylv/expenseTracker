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
        Schema::create('expenses', function (Blueprint $table) {
            $table->ulid('id')->primary();

    $table->foreignUlid('budget_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->foreignUlid('group_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->foreignUlid('created_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignUlid('expense_item_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->unsignedBigInteger('amount');

    $table->text('notes')->nullable();

    $table->date('expense_date');

    $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
