<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_proposal_data', function (Blueprint $table) {

            $table->string('on_campus_venue')
                ->nullable()
                ->after('venue_name');

            $table->string('off_campus_venue')
                ->nullable()
                ->after('on_campus_venue');

        });
    }

    public function down(): void
    {
        Schema::table('project_proposal_data', function (Blueprint $table) {

            $table->dropColumn([
                'on_campus_venue',
                'off_campus_venue',
            ]);

        });
    }
};