<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Services\MealDbService;
use Illuminate\Http\Request;

class RecipeWebController extends Controller
{
    protected $mealDbService;

    public function __construct(MealDbService $mealDbService)
    {
        $this->mealDbService = $mealDbService;
    }

    public function index(Request $request)
    {
        $query = Recipe::where('is_approved', true)->with('user')->withAvg('ratings', 'score');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ingredients', 'like', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $recipes = $query->latest()->paginate(12);
        $categories = $this->mealDbService->getCategories();

        $savedRecipeIds = [];
        $savedMealApiIds = [];
        if (auth()->check()) {
            $savedRecipeIds = auth()->user()->savedRecipes()->pluck('recipe_id')->filter()->toArray();
            $savedMealApiIds = auth()->user()->savedRecipes()->pluck('meal_api_id')->filter()->toArray();
        }

        return view('recipes.index', compact('recipes', 'categories', 'savedRecipeIds', 'savedMealApiIds'));
    }

    public function show($id)
    {
        $recipe = Recipe::with('user', 'ratings.user')->findOrFail($id);
        $recipe->increment('view_count');

        if (auth()->check()) {
            auth()->user()->recipeViews()->create([
                'recipe_id' => $id,
                'viewed_at' => now(),
            ]);
        }

        $averageRating = $recipe->ratings->avg('score');
        
        $isSaved = auth()->check() ? auth()->user()->savedRecipes()->where('recipe_id', $id)->exists() : false;

        return view('recipes.show', compact('recipe', 'averageRating', 'isSaved'));
    }

    public function showFromApi($mealId)
    {
        $recipe = $this->mealDbService->findById($mealId);

        if (!$recipe) {
            abort(404);
        }

        if (auth()->check()) {
            auth()->user()->recipeViews()->create([
                'meal_api_id' => $mealId,
                'viewed_at' => now(),
            ]);
        }

        $isSaved = auth()->check() ? auth()->user()->savedRecipes()->where('meal_api_id', $mealId)->exists() : false;

        return view('recipes.show-api', compact('recipe', 'isSaved'));
    }
}
