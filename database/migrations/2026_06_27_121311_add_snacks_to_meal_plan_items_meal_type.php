<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify enum to include snacks
        DB::statement("ALTER TABLE meal_plan_items MODIFY COLUMN meal_type ENUM('breakfast', 'lunch', 'dinner', 'snacks') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE meal_plan_items MODIFY COLUMN meal_type ENUM('breakfast', 'lunch', 'dinner') NOT NULL");
    }
};
