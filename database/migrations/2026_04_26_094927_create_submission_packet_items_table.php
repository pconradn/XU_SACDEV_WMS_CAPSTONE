<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_packet_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('packet_id')
                ->constrained('submission_packets')
                ->cascadeOnDelete();

            $table->string('type');
            $table->string('reference_number');

            $table->string('label')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('organization_name')->nullable();
            $table->text('remarks')->nullable();

            $table->enum('review_status', [
                'pending',
                'reviewed',
                'requires_revision'
            ])->default('pending');

            $table->timestamps();

            $table->index(['packet_id', 'type']);
            $table->index('review_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_packet_items');
    }
};