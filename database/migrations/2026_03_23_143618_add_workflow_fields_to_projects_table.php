<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {


            $table->date('implementation_start_date')->nullable()->after('description');
            $table->date('implementation_end_date')->nullable();

            $table->time('implementation_start_time')->nullable();
            $table->time('implementation_end_time')->nullable();

            $table->string('implementation_venue')->nullable();
            $table->enum('implementation_venue_type', ['on_campus', 'off_campus'])->nullable();


            $table->enum('workflow_status', [
                'planning',                 // no proposal yet
                'drafting',                 // proposal exists but not submitted
                'submitted',                // submitted for review
                'under_review',             // reviewer side
                'returned',                 // needs revision
                'approved',                 // approved for implementation
                'postponed',                // approved postponement
                'cancelled',                // approved cancellation
                'post_implementation',      // after activity
                'completed'                 // fully done
            ])->default('planning');


            $table->unsignedBigInteger('approved_proposal_id')->nullable();
            $table->unsignedBigInteger('approved_postponement_id')->nullable();
            $table->unsignedBigInteger('approved_cancellation_id')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {

            $table->dropColumn([
                'implementation_start_date',
                'implementation_end_date',
                'implementation_start_time',
                'implementation_end_time',
                'implementation_venue',
                'implementation_venue_type',
                'workflow_status',
                'approved_proposal_id',
                'approved_postponement_id',
                'approved_cancellation_id',
            ]);

        });
    }
};