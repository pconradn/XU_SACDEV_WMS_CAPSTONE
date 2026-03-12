<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_packet_receipts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('packet_id')
                ->constrained('submission_packets')
                ->cascadeOnDelete();

            $table->string('or_number');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_packet_receipts');
    }
};