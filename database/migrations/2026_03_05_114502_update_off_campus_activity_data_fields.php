<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('off_campus_activity_data', function (Blueprint $table) {

            $table->string('organization_name')->nullable()->after('project_document_id');

            $table->string('activity_name')->nullable();

            $table->string('inclusive_dates')->nullable();

            $table->string('venue_destination')->nullable();

            $table->timestamp('guidelines_acknowledged_at')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('off_campus_activity_data', function (Blueprint $table) {

            $table->dropColumn([
                'organization_name',
                'activity_name',
                'inclusive_dates',
                'venue_destination',
                'guidelines_acknowledged_at'
            ]);

        });
    }
};