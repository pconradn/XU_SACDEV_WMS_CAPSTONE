<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('officer_entries', function (Blueprint $table) {
            // B-3 fields
            $table->string('student_id_number', 32)->nullable()->after('position');
            $table->string('course_and_year', 255)->nullable()->after('student_id_number');
            $table->decimal('latest_qpi', 5, 2)->nullable()->after('course_and_year');
            $table->string('mobile_number', 32)->nullable()->after('latest_qpi');
            $table->unsignedInteger('sort_order')->nullable()->after('mobile_number');

            // Traceability back to B-3 item (optional but highly recommended)
            $table->unsignedBigInteger('source_officer_submission_item_id')->nullable()->after('sort_order');
            $table->index('source_officer_submission_item_id');

            // (Optional) Stronger uniqueness to prevent duplicates per org+sy
            // If you already enforce uniqueness elsewhere, skip this.
            $table->unique(['organization_id', 'school_year_id', 'email'], 'officer_entries_org_sy_email_unique');
        });
    }

    public function down(): void
    {
        Schema::table('officer_entries', function (Blueprint $table) {
            // Drop unique if added
            $table->dropUnique('officer_entries_org_sy_email_unique');

            $table->dropIndex(['source_officer_submission_item_id']);
            $table->dropColumn([
                'student_id_number',
                'course_and_year',
                'latest_qpi',
                'mobile_number',
                'sort_order',
                'source_officer_submission_item_id',
            ]);
        });
    }
};
