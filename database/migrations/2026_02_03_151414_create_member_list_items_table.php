<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_list_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('member_list_id');

            $table->string('full_name');
            $table->string('student_id_number', 50); 
            $table->string('course_and_year');
            $table->decimal('latest_qpi', 3, 2)->nullable(); 
            $table->string('mobile_number');

            $table->unsignedInteger('sort_order')->default(1);

            $table->timestamps();

            $table->foreign('member_list_id', 'mli_list_fk')
                ->references('id')->on('member_lists')
                ->cascadeOnDelete();

            $table->index('student_id_number', 'mli_student_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_list_items');
    }
};
