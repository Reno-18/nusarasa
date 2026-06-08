<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_plan_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_plan_template_id')->constrained('meal_plan_templates')->onDelete('cascade');
            $table->string('day_of_week'); // monday, tuesday, etc.
            $table->string('meal_time');   // breakfast, lunch, dinner, snack/camilan
            $table->foreignId('recipe_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('meal_api_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_plan_template_items');
    }
};
