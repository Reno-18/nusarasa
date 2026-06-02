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
        Schema::table('saved_recipes', function (Blueprint $table) {
            $table->string('meal_api_title')->nullable()->after('meal_api_id');
            $table->string('meal_api_image')->nullable()->after('meal_api_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saved_recipes', function (Blueprint $table) {
            $table->dropColumn(['meal_api_title', 'meal_api_image']);
        });
    }
};
