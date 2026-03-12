<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('selling_activity_report_data', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_document_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('activity_name');

            $table->date('selling_from');
            $table->date('selling_to');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selling_activity_report_data');
    }
};