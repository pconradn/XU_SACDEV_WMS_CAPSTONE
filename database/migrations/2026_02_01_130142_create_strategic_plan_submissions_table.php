<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('strategic_plan_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            
            $table->foreignId('target_school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            
            $table->foreignId('submitted_by_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('status')->default('draft');
            

            
            $table->string('org_acronym')->nullable();
            $table->string('org_name');
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();

            
            $table->string('logo_path')->nullable();
            $table->string('logo_original_name')->nullable();
            $table->string('logo_mime')->nullable();
            $table->unsignedBigInteger('logo_size_bytes')->nullable();

            
            $table->decimal('total_org_dev', 12, 2)->default(0);
            $table->decimal('total_student_services', 12, 2)->default(0);
            $table->decimal('total_community_involvement', 12, 2)->default(0);
            $table->decimal('total_overall', 12, 2)->default(0);

            
            $table->timestamp('submitted_to_moderator_at')->nullable();
            $table->timestamp('forwarded_to_sacdev_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            
            $table->foreignId('moderator_reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('moderator_reviewed_at')->nullable();
            $table->text('moderator_remarks')->nullable();

           
            $table->foreignId('sacdev_reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('sacdev_reviewed_at')->nullable();
            $table->text('sacdev_remarks')->nullable();

            $table->timestamps();

            
            $table->unique(
                ['organization_id', 'target_school_year_id'],
                'sps_org_target_uq'
            );

            
            $table->index(['target_school_year_id', 'status'], 'sps_sy_status_idx');
            $table->index(['organization_id', 'status'], 'sps_org_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_plan_submissions');
    }
};
