<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->text('mission')->nullable()->after('acronym');
            $table->text('vision')->nullable()->after('mission');

            $table->string('logo_path', 1024)->nullable()->after('vision');
            $table->string('logo_original_name', 255)->nullable()->after('logo_path');
            $table->string('logo_mime', 100)->nullable()->after('logo_original_name');
            $table->unsignedBigInteger('logo_size_bytes')->nullable()->after('logo_mime');

            $table->unsignedBigInteger('last_b1_submission_id')->nullable()->after('logo_size_bytes');
            $table->index('last_b1_submission_id', 'org_last_b1_idx');
        });

    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropIndex('org_last_b1_idx');

            $table->dropColumn([
                'org_acronym',
                'org_name',
                'mission',
                'vision',
                'logo_path',
                'logo_original_name',
                'logo_mime',
                'logo_size_bytes',
                'last_b1_submission_id',
            ]);
        });
    }
};
