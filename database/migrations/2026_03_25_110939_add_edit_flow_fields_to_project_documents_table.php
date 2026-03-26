<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_documents', function (Blueprint $table) {
            $table->boolean('edit_requested')
                ->default(false)
                ->after('status');

            $table->timestamp('edit_requested_at')
                ->nullable()
                ->after('edit_requested');

            $table->foreignId('edit_requested_by')
                ->nullable()
                ->after('edit_requested_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->text('edit_request_remarks')
                ->nullable()
                ->after('edit_requested_by');

            $table->boolean('edit_mode')
                ->default(false)
                ->after('edit_request_remarks');

            $table->boolean('edit_requires_full_approval')
                ->default(true)
                ->after('edit_mode');
        });
    }

    public function down(): void
    {
        Schema::table('project_documents', function (Blueprint $table) {
            $table->dropForeign(['edit_requested_by']);

            $table->dropColumn([
                'edit_requested',
                'edit_requested_at',
                'edit_requested_by',
                'edit_request_remarks',
                'edit_mode',
                'edit_requires_full_approval',
            ]);
        });
    }
};