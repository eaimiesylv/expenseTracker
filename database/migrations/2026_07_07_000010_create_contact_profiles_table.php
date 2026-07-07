<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('contact_profiles')) {
            Schema::create('contact_profiles', function (Blueprint $table) {
                $table->id();
                $table->morphs('contactable');
                $table->string('reminder_phone', 30)->nullable();
                $table->string('reminder_email')->nullable();
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['reminder_phone']);
                $table->index(['reminder_email']);
            });

            return;
        }

        if (! Schema::hasColumn('contact_profiles', 'contactable_type')) {
            Schema::table('contact_profiles', function (Blueprint $table) {
                $table->string('contactable_type')->nullable();
                $table->unsignedBigInteger('contactable_id')->nullable();
            });
        }

        $indexes = DB::select('SHOW INDEX FROM contact_profiles');
        $hasMorphIndex = collect($indexes)->contains(fn ($index) => $index->Key_name === 'contact_profiles_contactable_type_contactable_id_index');

        if (! $hasMorphIndex) {
            Schema::table('contact_profiles', function (Blueprint $table) {
                $table->index(['contactable_type', 'contactable_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_profiles');
    }
};
