<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('president_registrations', function (Blueprint $table) {
            $table->id();

            // Core context
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('target_school_year_id')->constrained('school_years')->restrictOnDelete();
            $table->foreignId('encoded_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Workflow
            $table->string('status', 40)->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            // SACDEV review
            $table->foreignId('sacdev_reviewed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('sacdev_remarks')->nullable();
            $table->timestamp('sacdev_reviewed_at')->nullable();

            // Optional revision/versioning
            $table->unsignedInteger('version')->default(1);

            // President identity
            $table->string('photo_id_path')->nullable(); // enforce required on submit, not in DB
            $table->string('full_name')->nullable();
            $table->string('course_and_year')->nullable();

            // Personal info
            $table->date('birthday')->nullable();
            $table->unsignedTinyInteger('age')->nullable(); // optional; you can compute later
            $table->string('sex', 20)->nullable();          // keep as string for flexibility
            $table->string('religion')->nullable();

            // Contact info
            $table->string('mobile_number', 30)->nullable();
            $table->string('city_landline', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('id_number', 50)->nullable();
            $table->string('provincial_landline', 30)->nullable();
            $table->string('facebook_url')->nullable();
            $table->text('home_address')->nullable();
            $table->text('city_address')->nullable();

            // Family info (nullable)
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_mobile', 30)->nullable();

            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_mobile', 30)->nullable();

            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_mobile', 30)->nullable();

            $table->unsignedTinyInteger('siblings_count')->nullable();

            // Education
            $table->string('high_school_name')->nullable();
            $table->string('high_school_address')->nullable();
            $table->string('high_school_year_graduated', 10)->nullable();

            $table->string('grade_school_name')->nullable();
            $table->string('grade_school_address')->nullable();
            $table->string('grade_school_year_graduated', 10)->nullable();

            $table->string('scholarship_name')->nullable();
            $table->string('scholarship_year_granted', 10)->nullable();

            // Other
            $table->text('skills_and_interests')->nullable();
            $table->boolean('certified')->default(false);

            $table->timestamps();

            // One registration per org per target SY
            $table->unique(
                ['organization_id', 'target_school_year_id'],
                'uq_presreg_org_sy'
            );

            // Helpful filters
            $table->index(['target_school_year_id', 'status'], 'ix_presreg_sy_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('president_registrations');
    }
};