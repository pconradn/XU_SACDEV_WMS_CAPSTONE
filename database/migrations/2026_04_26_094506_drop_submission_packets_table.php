<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // DROP CHILD FIRST
        Schema::dropIfExists('submission_packet_items');

        // THEN PARENT
        Schema::dropIfExists('submission_packets');
    }

    public function down(): void
    {
        //
    }
};