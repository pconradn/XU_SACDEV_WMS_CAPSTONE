<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('submission_packet_letters', function (Blueprint $table) {

            $table->string('control_number')->after('packet_id');

            $table->string('organization_name')->nullable()->change();

            $table->dropColumn('contact_person');
            $table->dropColumn('notes');

        });
    }

    public function down(): void
    {
        Schema::table('submission_packet_letters', function (Blueprint $table) {

            $table->dropColumn('control_number');

            $table->string('contact_person')->nullable();
            $table->text('notes')->nullable();

        });
    }
};