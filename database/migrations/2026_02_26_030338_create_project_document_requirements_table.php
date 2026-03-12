<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_document_requirements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('form_type_id');

            $table->boolean('is_required')->default(true);
            $table->unsignedBigInteger('set_by_user_id')->nullable();

            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->foreign('form_type_id')->references('id')->on('form_types')->cascadeOnDelete();
            $table->foreign('set_by_user_id')->references('id')->on('users')->nullOnDelete();

            $table->unique(['project_id', 'form_type_id'], 'uniq_project_form_requirement');
            $table->index(['project_id', 'is_required']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_document_requirements');
    }
};