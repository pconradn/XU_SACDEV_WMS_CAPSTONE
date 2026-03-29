<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        
        DB::statement("
            ALTER TABLE project_document_signatures
            MODIFY role ENUM(
                'president',
                'project_head',
                'treasurer',
                'finance_officer',
                'moderator',
                'sacdev_admin',
                'osa_admin'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            DELETE FROM project_document_signatures
            WHERE role = 'finance_officer'
        ");

        DB::statement("
            ALTER TABLE project_document_signatures
            MODIFY role ENUM(
                'president',
                'treasurer',
                'finance_officer',
                'moderator',
                'sacdev_admin',
                'osa_admin'
            ) NOT NULL
        ");
    }
};