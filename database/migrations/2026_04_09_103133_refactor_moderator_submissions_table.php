<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('moderator_submissions', function (Blueprint $table) {

            $table->dropColumn([
                'photo_id_path',
                'full_name',
                'birthday',
                'age',
                'sex',
                'religion',

                'mobile_number',
                'email',
                'landline',
                'facebook_url',
                'city_address',

                'skills_and_interests',

                'university_designation',
                'unit_department',
                'employment_status',
                'years_of_service',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('moderator_submissions', function (Blueprint $table) {

            $table->string('photo_id_path')->nullable();

            $table->string('full_name')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('age')->nullable();
            $table->string('sex')->nullable();
            $table->string('religion')->nullable();

            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            $table->string('landline')->nullable();
            $table->string('facebook_url')->nullable();
            $table->text('city_address')->nullable();

            $table->text('skills_and_interests')->nullable();

            $table->string('university_designation')->nullable();
            $table->string('unit_department')->nullable();
            $table->string('employment_status')->nullable();
            $table->integer('years_of_service')->nullable();
        });
    }
};