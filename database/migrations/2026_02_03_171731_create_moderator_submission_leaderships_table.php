<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moderator_submission_leaderships', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('moderator_submission_id');

            $table->string('organization_name')->nullable();
            $table->string('position')->nullable();
            $table->string('organization_address')->nullable();
            $table->string('inclusive_years', 30)->nullable();

            $table->unsignedInteger('sort_order')->default(1);

            $table->timestamps();

            $table->foreign('moderator_submission_id', 'msl_sub_fk')
                ->references('id')->on('moderator_submissions')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moderator_submission_leaderships');
    }
};
