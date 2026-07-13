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
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->ulid('id')->primary();

    $table->foreignUlid('bill_participant_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->unsignedBigInteger('amount');

    $table->enum('payment_method', [
        'cash',
        'bank_transfer',
        'card',
        'other'
    ])->default('cash');

    $table->text('notes')->nullable();

    $table->timestamp('payment_date');

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_payments');
    }
};
