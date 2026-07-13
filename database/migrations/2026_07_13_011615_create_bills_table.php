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
        Schema::create('bills', function (Blueprint $table) {
           $table->ulid('id')->primary();

    $table->foreignUlid('group_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->foreignUlid('created_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->string('title');

    $table->unsignedBigInteger('total_amount');

    $table->enum('split_type', [
        'equal',
        'fixed'
    ]);

    $table->date('due_date')->nullable();

    $table->enum('status', [
        'open',
        'completed',
        'cancelled'
    ])->default('open');

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
