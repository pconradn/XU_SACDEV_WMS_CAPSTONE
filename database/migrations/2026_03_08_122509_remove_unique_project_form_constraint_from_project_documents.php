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

        Schema::table('project_documents', function (Blueprint $table) {

            $table->unique(
                ['project_id','form_type_id'],
                'uniq_project_form_document'
            );

        });

    }
};