<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_member_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('student_id_number', 32)->nullable();
            $table->string('course_and_year')->nullable();
            $table->string('mobile_number', 32)->nullable();

            $table->foreignId('encoded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'school_year_id']);
            $table->index(['school_year_id']);
            $table->index(['user_id']);
            $table->index(['student_id_number']);

            $table->unique(
                ['organization_id', 'school_year_id', 'student_id_number'],
                'org_member_records_org_sy_student_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_member_records');
    }
};