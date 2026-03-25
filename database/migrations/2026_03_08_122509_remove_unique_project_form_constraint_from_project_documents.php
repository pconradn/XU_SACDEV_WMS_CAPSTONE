<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('project_documents', function (Blueprint $table) {

            $table->dropUnique('uniq_project_form_document');

        });

    }

    public function down(): void
    {
        DB::statement("
            DELETE pd1 FROM project_documents pd1
            INNER JOIN project_documents pd2
            WHERE 
                pd1.id > pd2.id
                AND pd1.project_id = pd2.project_id
                AND pd1.form_type_id = pd2.form_type_id
        ");

        Schema::table('project_documents', function (Blueprint $table) {
            $table->unique(
                ['project_id','form_type_id'],
                'uniq_project_form_document'
            );
        });
    }
};