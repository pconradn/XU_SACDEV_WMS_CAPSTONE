<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        DB::statement("
            ALTER TABLE project_document_signatures 
            MODIFY role ENUM(
                'project_head',
                'treasurer',
                'finance_officer',
                'president',
                'moderator',
                'sacdev_admin',
                'coa_officer'
            )
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE project_document_signatures 
            MODIFY role ENUM(
                'project_head',
                'treasurer',
                'president',
                'moderator',
                'sacdev_admin'
            )
        ");
    }
};
