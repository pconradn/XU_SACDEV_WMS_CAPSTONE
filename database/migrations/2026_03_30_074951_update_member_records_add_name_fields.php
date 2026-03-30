<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_member_records', function (Blueprint $table) {

            // REMOVE old
            $table->dropColumn('full_name');

            // ADD new structured fields
            $table->string('first_name')->after('user_id');
            $table->string('last_name')->after('first_name');
            $table->string('middle_initial', 5)->nullable()->after('last_name');

            $table->decimal('latest_qpi', 4, 2)->nullable()->after('mobile_number');
        });
    }

    public function down(): void
    {
        Schema::table('organization_member_records', function (Blueprint $table) {

            $table->string('full_name')->after('user_id');

            $table->dropColumn([
                'first_name',
                'last_name',
                'middle_initial',
                'latest_qpi'
            ]);
        });
    }
};