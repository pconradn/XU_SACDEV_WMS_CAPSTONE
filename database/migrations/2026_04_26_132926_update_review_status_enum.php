<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
        */
    public function up()
    {
        DB::statement("
            ALTER TABLE submission_packet_items 
            MODIFY review_status ENUM(
                'pending',
                'reviewed',
                'revision_required',
                'ready_for_claiming'
            ) DEFAULT 'pending'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE submission_packet_items 
            MODIFY review_status ENUM(
                'pending',
                'reviewed',
                'requires_revision'
            ) DEFAULT 'pending'
        ");
    }
};
