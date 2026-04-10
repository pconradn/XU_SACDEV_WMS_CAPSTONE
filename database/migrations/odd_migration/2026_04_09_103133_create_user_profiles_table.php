<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->string('photo_id_path')->nullable();

            $table->string('full_name');
            $table->string('course_and_year')->nullable();

            $table->date('birthday')->nullable();
            $table->string('sex')->nullable();
            $table->string('religion')->nullable();

            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            $table->string('landline')->nullable();
            $table->string('facebook_url')->nullable();

            $table->text('home_address')->nullable();
            $table->text('city_address')->nullable();

            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_mobile')->nullable();

            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_mobile')->nullable();

            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_mobile')->nullable();

            $table->integer('siblings_count')->nullable();

            $table->string('high_school_name')->nullable();
            $table->string('high_school_address')->nullable();
            $table->string('high_school_year_graduated')->nullable();

            $table->string('grade_school_name')->nullable();
            $table->string('grade_school_address')->nullable();
            $table->string('grade_school_year_graduated')->nullable();

            $table->string('scholarship_name')->nullable();
            $table->string('scholarship_year_granted')->nullable();

            $table->text('skills_and_interests')->nullable();


            $table->string('university_designation')->nullable();
            $table->string('unit_department')->nullable();
            $table->string('employment_status')->nullable();
            $table->integer('years_of_service')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};