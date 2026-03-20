<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('submission_packets', function (Blueprint $table) {

            $table->foreignId('project_document_id')
                ->nullable()
                ->change();

        });
    }

    public function down(): void
    {
        // Clean NULLs first BEFORE making NOT NULL
        DB::table('submission_packets')
            ->whereNull('project_document_id')
            ->update([
                'project_document_id' => 1 // or any valid ID
            ]);

        Schema::table('submission_packets', function (Blueprint $table) {
            $table->foreignId('project_document_id')
                ->nullable(false)
                ->change();
        });
    }
};