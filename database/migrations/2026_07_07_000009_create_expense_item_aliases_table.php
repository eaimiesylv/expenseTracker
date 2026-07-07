<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_item_aliases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_item_id')->constrained('expense_items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('alias');
            $table->string('normalized_alias')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['expense_item_id', 'is_active']);
            $table->index(['normalized_alias']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_item_aliases');
    }
};
