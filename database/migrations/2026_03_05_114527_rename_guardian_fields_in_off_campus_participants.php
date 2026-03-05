<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('off_campus_participants', function (Blueprint $table) {

            $table->renameColumn('guardian_name', 'parent_name');
            $table->renameColumn('guardian_mobile', 'parent_mobile');

        });
    }

    public function down(): void
    {
        Schema::table('off_campus_participants', function (Blueprint $table) {

            $table->renameColumn('parent_name', 'guardian_name');
            $table->renameColumn('parent_mobile', 'guardian_mobile');

        });
    }
};