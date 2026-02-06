<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('org_memberships', function (Blueprint $table) {
            $table->unsignedBigInteger('officer_entry_id')->nullable()->after('organization_id');
            $table->index(['officer_entry_id']);

           
            $table->foreign('officer_entry_id')
                ->references('id')
                ->on('officer_entries')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('org_memberships', function (Blueprint $table) {
            $table->dropForeign(['officer_entry_id']);
            $table->dropIndex(['officer_entry_id']);
            $table->dropColumn('officer_entry_id');
        });
    }
};
