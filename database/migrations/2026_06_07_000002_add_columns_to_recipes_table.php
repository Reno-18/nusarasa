<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->string('difficulty')->nullable()->after('image_url');
            $table->json('nutrition')->nullable()->after('difficulty');
            $table->json('tags')->nullable()->after('nutrition');
        });
    }

    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn(['difficulty', 'nutrition', 'tags']);
        });
    }
};
