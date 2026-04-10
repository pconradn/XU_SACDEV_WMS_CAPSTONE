<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {

            $table->string('prefix')->nullable()->after('photo_id_path');
            $table->string('first_name')->nullable()->after('prefix');
            $table->string('middle_initial', 5)->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_initial');

        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {

            $table->dropColumn([
                'prefix',
                'first_name',
                'middle_initial',
                'last_name',
            ]);
        });
    }
};