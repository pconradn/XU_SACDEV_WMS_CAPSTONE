<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('off_campus_activity_data', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_document_id')
                ->constrained('project_documents')
                ->cascadeOnDelete();

            $table->text('remarks')->nullable();

            $table->timestamps();

        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('off_campus_activity_data');
    }
};