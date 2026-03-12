<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('project_proposal_plan_of_actions', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('project_document_id');

            $table->date('date');

            $table->time('time')->nullable();

            $table->string('activity');

            $table->string('venue')->nullable();

            $table->timestamps();

            $table->foreign('project_document_id')
                ->references('id')
                ->on('project_documents')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_proposal_plan_of_actions');
    }
};