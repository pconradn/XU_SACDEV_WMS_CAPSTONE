<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('officer_entries', function (Blueprint $table) {

            // system role identifier
            $table->string('major_officer_role')
                ->nullable()
                ->after('position');

            // fast boolean check
            $table->boolean('is_major_officer')
                ->default(false)
                ->after('major_officer_role');

            // replace latest_qpi with structured QPI fields
            $table->decimal('first_sem_qpi', 3, 2)
                ->nullable()
                ->after('course_and_year');

            $table->decimal('second_sem_qpi', 3, 2)
                ->nullable()
                ->after('first_sem_qpi');

            $table->decimal('intersession_qpi', 3, 2)
                ->nullable()
                ->after('second_sem_qpi');

            // optional: probation flag for SACDEV monitoring
            $table->boolean('is_under_probation')
                ->default(false)
                ->after('intersession_qpi');

            // index for fast lookup
            $table->index(
                ['organization_id', 'school_year_id', 'major_officer_role'],
                'oe_major_role_idx'
            );

        });
    }

    public function down(): void
    {
        Schema::table('officer_entries', function (Blueprint $table) {

            $table->dropIndex('oe_major_role_idx');

            $table->dropColumn([
                'major_officer_role',
                'is_major_officer',
                'first_sem_qpi',
                'second_sem_qpi',
                'intersession_qpi',
                'is_under_probation',
            ]);

        });
    }
};