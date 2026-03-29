<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
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
                'osa_admin',
                'finance_officer'
            )
        ");
    }

    public function down(): void
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
                'osa_admin',
                'finance_officer'
            )
        ");
    }
};