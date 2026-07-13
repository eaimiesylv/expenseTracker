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
        Schema::create('budget_categories', function (Blueprint $table) {
             $table->ulid('id')->primary();

    
            $table->foreignUlid('owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('name');

            $table->text('description')->nullable();


            $table->unique(['owner_id', 'name']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_categories');
    }
};
