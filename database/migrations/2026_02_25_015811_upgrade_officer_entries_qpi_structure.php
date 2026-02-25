<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('officer_entries', function (Blueprint $table) {

           
            $table->renameColumn('first_sem_qpi', 'prev_first_sem_qpi');
            $table->renameColumn('second_sem_qpi', 'prev_second_sem_qpi');
            $table->renameColumn('intersession_qpi', 'prev_intersession_qpi');

        });

        Schema::table('officer_entries', function (Blueprint $table) {

            
            $table->decimal('current_first_sem_qpi', 3, 2)->nullable()->after('prev_intersession_qpi');
            $table->decimal('current_second_sem_qpi', 3, 2)->nullable()->after('current_first_sem_qpi');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
