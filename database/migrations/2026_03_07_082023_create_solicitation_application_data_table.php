<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitation_application_data', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_document_id')
                  ->constrained('project_documents')
                  ->cascadeOnDelete();

            $table->string('activity_name')->nullable();

            $table->text('purpose')->nullable();

            $table->date('duration_from')->nullable();
            $table->date('duration_to')->nullable();

            $table->decimal('target_amount', 12, 2)->nullable();

            $table->integer('desired_letter_count')->nullable();

            $table->boolean('target_student_orgs')->default(false);
            $table->boolean('target_xu_officers')->default(false);
            $table->boolean('target_private_individuals')->default(false);
            $table->boolean('target_alumni')->default(false);
            $table->boolean('target_private_companies')->default(false);

            $table->boolean('target_others')->default(false);
            $table->string('target_others_specify')->nullable();

            $table->string('letter_draft_path')->nullable();

            $table->integer('approved_letter_count')->nullable();
            $table->string('control_numbers_series')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitation_application_data');
    }
};