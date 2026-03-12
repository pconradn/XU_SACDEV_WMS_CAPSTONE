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

            $table->string('packet_code')->unique();

            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('project_document_id')
                ->constrained('project_documents')
                ->cascadeOnDelete();

            // document checklist
            $table->boolean('has_liquidation_report')->default(true);
            $table->boolean('has_disbursement_voucher')->default(false);
            $table->boolean('has_collection_report')->default(false);
            $table->boolean('has_certificates')->default(false);
            $table->boolean('has_receipts')->default(true);

            // workflow
            $table->enum('status', [
                'generated',
                'submitted_by_project_head',
                'received_by_sacdev',
                'verified_by_sacdev',
                'forwarded_to_finance'
            ])->default('generated');

            // tracking
            $table->foreignId('generated_by')->nullable()->constrained('users');
            $table->timestamp('generated_at')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->foreignId('received_by')->nullable()->constrained('users');
            $table->timestamp('received_at')->nullable();

            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();

            $table->timestamp('forwarded_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_packets');
    }
};
