<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {

            $table->boolean('requires_clearance')
                  ->default(false)
                  ->after('target_date');

            $table->string('clearance_reference')
                  ->nullable()
                  ->unique()
                  ->after('requires_clearance');

            $table->string('clearance_status')
                  ->nullable()
                  ->after('clearance_reference');
                 

            $table->string('clearance_file_path')
                  ->nullable()
                  ->after('clearance_status');

            $table->timestamp('clearance_required_at')
                  ->nullable()
                  ->after('clearance_file_path');

            $table->timestamp('clearance_uploaded_at')
                  ->nullable()
                  ->after('clearance_required_at');

            $table->timestamp('clearance_verified_at')
                  ->nullable()
                  ->after('clearance_uploaded_at');

            $table->text('clearance_remarks')
                  ->nullable()
                  ->after('clearance_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {

            $table->dropColumn([
                'requires_clearance',
                'clearance_reference',
                'clearance_status',
                'clearance_file_path',
                'clearance_required_at',
                'clearance_uploaded_at',
                'clearance_verified_at',
                'clearance_remarks',
            ]);

        });
    }
};