<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE submission_packets
            MODIFY status ENUM(
                'generated',
                'under_review',
                'reviewed',
                'ready_for_claiming'
            ) DEFAULT 'generated'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE submission_packets
            MODIFY status ENUM(
                'generated',
                'submitted_by_project_head',
                'received_by_sacdev',
                'verified_by_sacdev',
                'forwarded_to_finance'
            ) DEFAULT 'generated'
        ");
    }
};