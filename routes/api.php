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

// AUTHENTICATED
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Chef only
    Route::middleware('role:chef,admin')->group(function () {
        Route::post('/recipes', [RecipeController::class, 'store']);
        Route::put('/recipes/{id}', [RecipeController::class, 'update']);
        Route::delete('/recipes/{id}', [RecipeController::class, 'destroy']);
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

