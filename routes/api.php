<?php

use App\Http\Controllers\Api\MealPlanController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\SavedRecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// PUBLIC
Route::get('/recipes', [RecipeController::class, 'index']);
Route::get('/recipes/{id}', [RecipeController::class, 'show']);
Route::get('/recipes/{id}/ratings', [RatingController::class, 'index']);
Route::get('/leaderboard/chefs', [App\Http\Controllers\Api\LeaderboardController::class, 'index']);
Route::post('/recipes/by-ingredients', [App\Http\Controllers\Api\RecommendationController::class, 'getByIngredients']);

// AUTHENTICATED
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/recommendations', [App\Http\Controllers\Api\RecommendationController::class, 'getRecommendations']);
    Route::get('/user/points', [App\Http\Controllers\Api\GamificationController::class, 'getPoints']);
    Route::get('/user/badges', [App\Http\Controllers\Api\GamificationController::class, 'getBadges']);
    Route::post('/meal-plan-templates/{id}/apply', [App\Http\Controllers\Api\MealPlanTemplateController::class, 'apply']);

    // Chef only
    Route::middleware('role:chef,admin')->group(function () {
        Route::post('/recipes', [RecipeController::class, 'store']);
        Route::put('/recipes/{id}', [RecipeController::class, 'update']);
        Route::delete('/recipes/{id}', [RecipeController::class, 'destroy']);
        Route::post('/recipes/auto-tags', [App\Http\Controllers\Api\RecommendationController::class, 'getAutoTags']);
        Route::apiResource('/meal-plan-templates', App\Http\Controllers\Api\MealPlanTemplateController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::patch('/recipes/{id}/approve', [RecipeController::class, 'approve']);
    });

    // All authenticated users
    Route::post('/recipes/{id}/ratings', [RatingController::class, 'store']);
    Route::delete('/meal-plans/items/{id}', [MealPlanController::class, 'destroyItem']);
    Route::apiResource('/meal-plans', MealPlanController::class);
    Route::post('/saved-recipes', [SavedRecipeController::class, 'store'])->name('api.saved-recipes.store');
    Route::delete('/saved-recipes/{id}', [SavedRecipeController::class, 'destroy'])->name('api.saved-recipes.destroy');
});

