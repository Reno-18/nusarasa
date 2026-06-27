<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\ChefRecipeController;
use App\Http\Controllers\MealPlanWebController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeWebController;
use App\Http\Controllers\SavedRecipeWebController;
use App\Http\Controllers\UserRecipeController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', function () {
    $recommended = collect();
    if (auth()->check()) {
        $recommendationService = app(\App\Services\RecommendationService::class);
        $recommended = $recommendationService->getRecommendations(auth()->user());
    }
    return view('welcome', compact('recommended'));
})->name('home');

Route::get('/recipes/by-ingredients', [App\Http\Controllers\RecipeIngredientController::class, 'index'])->name('recipes.by-ingredients');
Route::get('/recipes', [RecipeWebController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{id}', [RecipeWebController::class, 'show'])->name('recipes.show');
Route::get('/recipes/api/{mealId}', [RecipeWebController::class, 'showFromApi'])->name('recipes.show-api');
Route::get('/chefs', [ChefController::class, 'index'])->name('chefs.index');
Route::get('/leaderboard/chefs', [App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.chefs');

// Authenticated users
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isChef()) {
            return redirect()->route('chef.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/saved', [SavedRecipeWebController::class, 'index'])->name('saved.index');
    Route::get('/meal-plan/shopping-list', [MealPlanWebController::class, 'shoppingList'])->name('meal-plan.shopping-list');
    Route::post('/meal-plan/shopping-list', [MealPlanWebController::class, 'generateShoppingList'])->name('meal-plan.shopping-list.generate');
    Route::post('/meal-plan/shopping-list/pdf', [MealPlanWebController::class, 'downloadPdf'])->name('meal-plan.download-pdf');
    Route::delete('/meal-plan/items/{id}', [MealPlanWebController::class, 'destroyItem'])->name('meal-plan.items.destroy');
    Route::get('/meal-plan', [MealPlanWebController::class, 'index'])->name('meal-plan.index');
    Route::get('/meal-plan/templates', [MealPlanWebController::class, 'templates'])->name('meal-plan.templates');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/leaderboard', [ProfileController::class, 'toggleLeaderboard'])->name('profile.leaderboard-toggle');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Any authenticated user can submit a recipe (pending admin approval)
    Route::get('/resep/buat', [UserRecipeController::class, 'create'])->name('user.recipes.create');
    Route::post('/resep/buat', [UserRecipeController::class, 'store'])->name('user.recipes.store');
});

// Chef
Route::middleware(['auth', 'role:chef,admin'])->prefix('chef')->name('chef.')->group(function () {
    Route::get('/dashboard', [ChefController::class, 'dashboard'])->name('dashboard');
    Route::resource('/recipes', ChefRecipeController::class)->except(['index', 'show']);
    Route::resource('/meal-plan-templates', App\Http\Controllers\Chef\MealPlanTemplateController::class);
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/recipes', [AdminController::class, 'recipes'])->name('recipes');
    Route::patch('/recipes/{id}/approve', [AdminController::class, 'approveRecipe'])->name('recipes.approve');
    Route::delete('/recipes/{id}', [AdminController::class, 'destroyRecipe'])->name('recipes.destroy');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.update-role');
    Route::patch('/users/{id}/leaderboard', [AdminController::class, 'toggleLeaderboard'])->name('users.toggle-leaderboard');
});

require __DIR__.'/auth.php';

