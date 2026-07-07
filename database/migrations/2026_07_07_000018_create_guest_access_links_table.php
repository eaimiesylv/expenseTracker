<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_access_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('ledger_id')->nullable()->constrained('ledgers')->nullOnDelete()->cascadeOnUpdate();
            $table->string('share_ulid')->unique();
            $table->string('share_hash')->unique();
            $table->string('password')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('last_accessed_at')->nullable();
            $table->boolean('is_revoked')->default(false);
            $table->boolean('is_read_only')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['group_id', 'is_revoked']);
            $table->index(['ledger_id', 'is_revoked']);
            $table->index(['expires_at', 'is_revoked']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_access_links');
    }
};
