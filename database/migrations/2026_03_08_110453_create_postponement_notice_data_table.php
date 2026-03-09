<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postponement_notice_data', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_document_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('reason')->nullable();

            $table->date('new_date')->nullable();

            $table->time('new_start_time')->nullable();
            $table->time('new_end_time')->nullable();

            $table->string('venue')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postponement_notice_data');
    }
};