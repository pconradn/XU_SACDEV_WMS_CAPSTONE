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
        Schema::create('project_form_requirements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('form_type_id')
                ->constrained('form_types')
                ->cascadeOnDelete();

            $table->string('rule_key');


  
            $table->string('label')->nullable();
            $table->text('description')->nullable();

   
            $table->boolean('is_active')->default(true);

       
            $table->integer('sort_order')->default(0);

            $table->timestamps();

      
            $table->unique(['form_type_id', 'rule_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_form_requirements');
    }
};