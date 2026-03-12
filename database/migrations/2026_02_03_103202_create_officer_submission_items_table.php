<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('officer_submission_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('officer_submission_id');

            $table->string('position');
            $table->string('officer_name');

            
            $table->string('student_id_number', 50);

            $table->string('course_and_year');

            
            $table->decimal('latest_qpi', 3, 2)->nullable();

            $table->string('mobile_number');

            $table->unsignedInteger('sort_order')->default(1);

            $table->timestamps();

            $table->foreign('officer_submission_id', 'osi_submission_fk')
                ->references('id')->on('officer_submissions')
                ->cascadeOnDelete();

            
            $table->index('student_id_number', 'osi_student_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officer_submission_items');
    }
};
