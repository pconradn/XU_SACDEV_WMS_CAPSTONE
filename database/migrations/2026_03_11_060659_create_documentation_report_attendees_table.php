<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentation_report_attendees', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('project_document_id');

            $table->string('name');

            $table->string('affiliation')->nullable();

            $table->string('designation')->nullable();

            $table->timestamps();

            $table->foreign('project_document_id')
                ->references('id')
                ->on('project_documents')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentation_report_attendees');
    }
};