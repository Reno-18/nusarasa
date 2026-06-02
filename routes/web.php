<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\ChefRecipeController;
use App\Http\Controllers\MealPlanWebController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeWebController;
use App\Http\Controllers\SavedRecipeWebController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/recipes', [RecipeWebController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{id}', [RecipeWebController::class, 'show'])->name('recipes.show');
Route::get('/recipes/api/{mealId}', [RecipeWebController::class, 'showFromApi'])->name('recipes.show-api');
Route::get('/chefs', [ChefController::class, 'index'])->name('chefs.index');

// Authenticated users
Route::middleware(['auth'])->group(function () {
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
    Route::get('/meal-plan', [MealPlanWebController::class, 'index'])->name('meal-plan.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Chef
Route::middleware(['auth', 'role:chef,admin'])->prefix('chef')->name('chef.')->group(function () {
    Route::get('/dashboard', [ChefController::class, 'dashboard'])->name('dashboard');
    Route::resource('/recipes', ChefRecipeController::class)->except(['index', 'show']);
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/recipes', [AdminController::class, 'recipes'])->name('recipes');
    Route::patch('/recipes/{id}/approve', [AdminController::class, 'approveRecipe'])->name('recipes.approve');
    Route::delete('/recipes/{id}', [AdminController::class, 'destroyRecipe'])->name('recipes.destroy');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.update-role');
});

require __DIR__.'/auth.php';

