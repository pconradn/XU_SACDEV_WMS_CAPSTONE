<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // USERS
        Schema::table('users', function (Blueprint $table) {
            $table->string('prefix')->nullable()->after('name');
            $table->string('first_name')->nullable()->after('prefix');
            $table->string('middle_initial', 5)->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_initial');
        });

        // OFFICER SUBMISSION ITEMS
        Schema::table('officer_submission_items', function (Blueprint $table) {
            $table->string('prefix')->nullable()->after('officer_name');
            $table->string('first_name')->nullable()->after('prefix');
            $table->string('middle_initial', 5)->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_initial');
        });

        // OFFICER ENTRIES
        Schema::table('officer_entries', function (Blueprint $table) {
            $table->string('prefix')->nullable()->after('full_name');
            $table->string('first_name')->nullable()->after('prefix');
            $table->string('middle_initial', 5)->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_initial');
        });
    }

    public function down(): void
    {
        // USERS
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'prefix',
                'first_name',
                'middle_initial',
                'last_name',
            ]);
        });

        // OFFICER SUBMISSION ITEMS
        Schema::table('officer_submission_items', function (Blueprint $table) {
            $table->dropColumn([
                'prefix',
                'first_name',
                'middle_initial',
                'last_name',
            ]);
        });

        // OFFICER ENTRIES
        Schema::table('officer_entries', function (Blueprint $table) {
            $table->dropColumn([
                'prefix',
                'first_name',
                'middle_initial',
                'last_name',
            ]);
        });
    }
};