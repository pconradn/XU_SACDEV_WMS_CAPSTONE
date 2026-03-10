<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees_collection_report_data', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_document_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('activity_name');

            $table->text('purpose');

            $table->date('collection_from');
            $table->date('collection_to');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees_collection_report_data');
    }
};