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
        Schema::create('paitent_meal_plannings', function (Blueprint $table) {
            $table->id();
            $table->uuid('patient_id')->unique();
            $table->date('planned_date');
            $table->string('total_calories');
            $table->string('total_fats');
            $table->string('total_carbs');
            $table->string('total_proteins');
            $table->boolean('is_active');
            $table->string('created_by');
            $table->dateTime('created_at', $precision = 0);
            $table->string('updated_by');
            $table->dateTime('updated_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paitent_meal_plannings');
    }
};
