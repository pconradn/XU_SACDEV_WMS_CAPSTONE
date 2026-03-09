<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        DB::statement("
            ALTER TABLE form_types 
            MODIFY phase ENUM(
                'pre_implementation',
                'post_implementation',
                'off-campus',
                'other',
                'notice'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE form_types
            SET phase = 'pre_implementation'
            WHERE phase NOT IN ('pre_implementation', 'post_implementation')
        ");

        DB::statement("
            ALTER TABLE form_types
            MODIFY phase ENUM(
                'pre_implementation',
                'post_implementation'
            ) NOT NULL
        ");
    }
};