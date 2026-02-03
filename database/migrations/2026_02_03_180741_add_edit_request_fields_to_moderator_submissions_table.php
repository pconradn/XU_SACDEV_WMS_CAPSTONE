<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('moderator_submissions', function (Blueprint $table) {
            $table->boolean('edit_requested')->default(false)->after('status');
            $table->dateTime('edit_requested_at')->nullable()->after('edit_requested');

            $table->unsignedBigInteger('edit_requested_by_user_id')->nullable()->after('edit_requested_at');
            $table->text('edit_request_message')->nullable()->after('edit_requested_by_user_id');

            // short FK name to avoid 1059 identifier too long
            $table->foreign('edit_requested_by_user_id', 'ms_edit_req_by_fk')
                ->references('id')->on('users')
                ->nullOnDelete();

            $table->index(['edit_requested', 'edit_requested_at'], 'ms_edit_req_idx');
        });
    }

    public function down(): void
    {
        Schema::table('moderator_submissions', function (Blueprint $table) {
            $table->dropForeign('ms_edit_req_by_fk');
            $table->dropIndex('ms_edit_req_idx');

            $table->dropColumn([
                'edit_requested',
                'edit_requested_at',
                'edit_requested_by_user_id',
                'edit_request_message',
            ]);
        });
    }
};
