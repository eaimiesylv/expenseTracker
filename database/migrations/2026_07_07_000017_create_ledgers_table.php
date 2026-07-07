<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('ledger_type_id')->nullable()->constrained('ledger_types')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('visibility')->default('private');
            $table->boolean('is_archived')->default(false);
            $table->foreignId('default_currency_id')->nullable()->constrained('currencies')->nullOnDelete()->cascadeOnUpdate();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['group_id', 'is_archived']);
            $table->index(['owner_id', 'visibility']);
            $table->index(['ledger_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
