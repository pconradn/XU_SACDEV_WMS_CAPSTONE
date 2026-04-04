<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_packet_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('external_packet_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', [
                'form',
                'clearance',
                'file',
                'other'
            ]);

            $table->string('label');

            $table->string('form_type_code')->nullable();
            $table->foreignId('document_id')->nullable()->constrained('project_documents')->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_packet_items');
    }
};