<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_packet_dvs', function (Blueprint $table) {
            $table->id();

            
            $table->foreignId('packet_id')
                ->constrained('submission_packets')
                ->cascadeOnDelete();

           
            $table->string('dv_reference')->nullable();
           

            $table->string('dv_label')->nullable();
      

            $table->decimal('amount', 12, 2)->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_packet_dvs');
    }
};