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
        Schema::table('documentation_report_data', function (Blueprint $table) {

            $table->date('implementation_date')
                ->nullable()
                ->after('project_document_id');

            $table->time('implementation_start_time')
                ->nullable();

            $table->time('implementation_end_time')
                ->nullable();

            $table->string('on_campus_venue')
                ->nullable();

            $table->string('off_campus_venue')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentation_report_data', function (Blueprint $table) {
            $table->dropColumn([
                'implementation_date',
                'implementation_start_time',
                'implementation_end_time',
                'on_campus_venue',
                'off_campus_venue'
            ]);
        });
    }
};
