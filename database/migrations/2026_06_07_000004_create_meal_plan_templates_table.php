<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_plan_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chef_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('goal')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_plan_templates');
    }
};
