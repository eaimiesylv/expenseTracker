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
        Schema::create('bill_participants', function (Blueprint $table) {
           $table->ulid('id')->primary();

    $table->foreignUlid('bill_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignUlid('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->unsignedBigInteger('assigned_amount');

    $table->unsignedBigInteger('amount_paid')->default(0);

    $table->enum('status', [
        'pending',
        'partial',
        'paid'
    ])->default('pending');

    $table->timestamps();

    $table->unique(['bill_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_participants');
    }
};
