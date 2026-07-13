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
        Schema::create('budget_contributions', function (Blueprint $table) {
            $table->ulid('id')->primary();

    $table->foreignUlid('budget_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignUlid('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->unsignedBigInteger('expected_amount')->default(0);

    $table->unsignedBigInteger('paid_amount')->default(0);

    $table->enum('status', [
        'pending',
        'partial',
        'paid'
    ])->default('pending');

    $table->timestamp('paid_at')->nullable();

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_contributions');
    }
};
