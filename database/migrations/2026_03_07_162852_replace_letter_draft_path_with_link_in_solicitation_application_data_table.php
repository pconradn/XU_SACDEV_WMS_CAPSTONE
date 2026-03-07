<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitation_application_data', function (Blueprint $table) {
            $table->string('letter_draft_link')->nullable()->after('target_others_specify');
            $table->dropColumn('letter_draft_path');
        });
    }

    public function down(): void
    {
        Schema::table('solicitation_application_data', function (Blueprint $table) {
            $table->string('letter_draft_path')->nullable()->after('target_others_specify');
            $table->dropColumn('letter_draft_link');
        });
    }
};