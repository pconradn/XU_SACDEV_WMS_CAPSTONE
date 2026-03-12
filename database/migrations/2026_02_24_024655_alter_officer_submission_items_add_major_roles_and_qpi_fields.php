<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('officer_submission_items', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Major Officer Structure
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_major_officer')
                ->default(false)
                ->after('position');

            $table->string('major_officer_role', 50)
                ->nullable()
                ->after('is_major_officer');
                // allowed values:
                // president
                // vice_president
                // treasurer
                // auditor


            /*
            |--------------------------------------------------------------------------
            | New QPI Structure 
            |--------------------------------------------------------------------------
            */

            $table->decimal('first_sem_qpi', 3, 2)
                ->nullable()
                ->after('course_and_year');

            $table->decimal('second_sem_qpi', 3, 2)
                ->nullable()
                ->after('first_sem_qpi');

            $table->decimal('intersession_qpi', 3, 2)
                ->nullable()
                ->after('second_sem_qpi');




            $table->boolean('propagated_to_memberships')
                ->default(false)
                ->after('sort_order');

            $table->timestamp('propagated_at')
                ->nullable()
                ->after('propagated_to_memberships');




            $table->index(
                ['student_id_number', 'major_officer_role'],
                'osi_student_major_role_idx'
            );

        });
    }

    public function down(): void
    {
        Schema::table('officer_submission_items', function (Blueprint $table) {

            $table->dropIndex('osi_student_major_role_idx');

            $table->dropColumn([
                'is_major_officer',
                'major_officer_role',
                'first_sem_qpi',
                'second_sem_qpi',
                'intersession_qpi',
                'propagated_to_memberships',
                'propagated_at',
            ]);

        });
    }
};