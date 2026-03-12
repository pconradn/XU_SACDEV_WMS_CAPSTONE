<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moderator_submissions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('target_school_year_id');
            $table->unsignedBigInteger('moderator_user_id');

            // link to assignment/term (optional but recommended)
            $table->unsignedBigInteger('org_moderator_term_id')->nullable();

            $table->string('status', 30)->default('draft');

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            // SACDEV review
            $table->unsignedBigInteger('sacdev_reviewed_by_user_id')->nullable();
            $table->text('sacdev_remarks')->nullable();
            $table->timestamp('sacdev_reviewed_at')->nullable();

            // Photo ID
            $table->string('photo_id_path')->nullable();

            // Identity
            $table->string('full_name')->nullable();

            // Personal info
            $table->date('birthday')->nullable();
            $table->unsignedTinyInteger('age')->nullable(); // optional; can be derived
            $table->string('sex', 20)->nullable();
            $table->string('religion')->nullable();

            // Employment info
            $table->string('university_designation')->nullable();
            $table->string('unit_department')->nullable();
            $table->string('employment_status')->nullable();
            $table->unsignedSmallInteger('years_of_service')->nullable();

            // Contact info
            $table->string('mobile_number', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('landline', 30)->nullable();
            $table->string('facebook_url')->nullable();
            $table->text('city_address')->nullable();

            // Questions
            $table->boolean('was_moderator_before')->default(false);
            $table->string('moderated_org_name')->nullable(); // if yes

            $table->boolean('served_nominating_org_before')->default(false);
            $table->unsignedSmallInteger('served_nominating_org_years')->nullable();

            // Skills / interests
            $table->text('skills_and_interests')->nullable();

            $table->unsignedInteger('version')->default(0);

            $table->timestamps();

            // FK short names
            $table->foreign('organization_id', 'ms_org_fk')
                ->references('id')->on('organizations')
                ->cascadeOnDelete();

            $table->foreign('target_school_year_id', 'ms_sy_fk')
                ->references('id')->on('school_years')
                ->cascadeOnDelete();

            $table->foreign('moderator_user_id', 'ms_mod_user_fk')
                ->references('id')->on('users')
                ->cascadeOnDelete();

            $table->foreign('org_moderator_term_id', 'ms_term_fk')
                ->references('id')->on('org_moderator_terms')
                ->nullOnDelete();

            $table->foreign('sacdev_reviewed_by_user_id', 'ms_sacdev_by_fk')
                ->references('id')->on('users')
                ->nullOnDelete();

            // one submission per org+target SY (assuming one moderator per org per SY)
            $table->unique(['organization_id', 'target_school_year_id'], 'ms_org_sy_unique');

            $table->index(['status', 'target_school_year_id'], 'ms_status_sy_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moderator_submissions');
    }
};
