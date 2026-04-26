<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_packets', function (Blueprint $table) {

            $table->id();

            $table->string('packet_code');

            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('project_document_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('status', [
                'generated',
                'submitted',
                'under_review',
                'returned',
                'ready_for_claiming',
                'received'
            ])->default('generated');

            $table->text('remarks')->nullable();
            $table->text('return_remarks')->nullable();

            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('generated_at')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('received_at')->nullable();

            $table->foreignId('returned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('returned_at')->nullable();

            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('ready_for_claiming_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_packets');
    }
};