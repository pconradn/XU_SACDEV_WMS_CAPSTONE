<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up(): void
    {
        Schema::create('off_campus_participants', function (Blueprint $table) {

            $table->id();

            $table->foreignId('off_campus_activity_data_id')
                ->constrained('off_campus_activity_data')
                ->cascadeOnDelete();

            $table->string('student_name');
            $table->string('course_year')->nullable();

            $table->string('student_mobile')->nullable();

            $table->string('guardian_name')->nullable();
            $table->string('guardian_mobile')->nullable();

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('off_campus_participants');
    }
};