<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('recipient_guest_id')->nullable()->constrained('guest_access_links')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('contact_profile_id')->nullable()->constrained('contact_profiles')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('channel_id')->nullable()->constrained('reminder_channels')->nullOnDelete()->cascadeOnUpdate();
            $table->string('reminder_type');
            $table->string('remindable_type');
            $table->unsignedBigInteger('remindable_id');
            $table->string('status')->default('pending');
            $table->string('delivery_provider')->nullable();
            $table->unsignedInteger('attempt_count')->default(0);
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('acknowledged_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'scheduled_at']);
            $table->index(['remindable_type', 'remindable_id']);
            $table->index(['recipient_user_id', 'status']);
            $table->index(['recipient_guest_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
