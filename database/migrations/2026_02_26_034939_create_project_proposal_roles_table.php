<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('project_proposal_roles', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('project_document_id');

            
            $table->string('role_name');

            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('project_document_id')
                ->references('id')
                ->on('project_documents')
                ->cascadeOnDelete();

            $table->index('project_document_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_proposal_roles');
    }
};