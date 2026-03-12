<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentation_report_data', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('project_document_id')->unique();

            // Evaluation section
            $table->boolean('objectives_met')->nullable();
            $table->text('contributing_factors')->nullable();

            $table->integer('expected_participants')->nullable();
            $table->integer('actual_participants')->nullable();

            $table->unsignedTinyInteger('implementation_rating')->nullable(); 
            // rating 1–5

            // Project Implementation section
            $table->text('pre_implementation_stage')->nullable();
            $table->text('implementation_stage')->nullable();
            $table->text('post_implementation_stage')->nullable();
            $table->text('recommendations')->nullable();

            // Financial report
            $table->decimal('proposed_budget', 12, 2)->nullable();
            $table->decimal('actual_budget', 12, 2)->nullable();
            $table->decimal('balance', 12, 2)->nullable();

            // Photo documentation
            $table->string('photo_document_path')->nullable();

            $table->timestamps();

            $table->foreign('project_document_id')
                ->references('id')
                ->on('project_documents')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentation_report_data');
    }
};