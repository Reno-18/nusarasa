<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\RecipeTag;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    /**
     * Get recipe recommendations for a user.
     */
    public function getRecommendations(User $user, int $limit = 6): Collection
    {
        // 1. Get viewed and saved recipes
        $viewedRecipeIds = $user->recipeViews()->pluck('recipe_id')->filter()->unique();
        $savedRecipeIds = $user->savedRecipes()->pluck('recipe_id')->filter()->unique();
        $viewedOrSavedRecipeIds = $viewedRecipeIds->merge($savedRecipeIds)->unique();

        // 2. Identify top categories and tags from history
        $categories = Recipe::whereIn('id', $viewedOrSavedRecipeIds)->pluck('category')->filter()->unique();
        $tags = RecipeTag::whereIn('recipe_id', $viewedOrSavedRecipeIds)->pluck('tag')->filter()->unique();

        // 3. Query recommended recipes based on similar categories and tags
        $recommendedQuery = Recipe::where('is_approved', true)
            ->whereNotIn('id', $viewedOrSavedRecipeIds);

        if ($categories->isNotEmpty() || $tags->isNotEmpty()) {
            $recommendedQuery->where(function ($query) use ($categories, $tags) {
                if ($categories->isNotEmpty()) {
                    $query->whereIn('category', $categories);
                }
                if ($tags->isNotEmpty()) {
                    $query->orWhereHas('recipeTags', function ($q) use ($tags) {
                        $q->whereIn('tag', $tags);
                    });
                }
            });
        } else {
            // Force no results if there's no history so we fall back cleanly
            $recommendedQuery->whereRaw('1 = 0');
        }

        $recommended = $recommendedQuery->withAvg('ratings', 'score')
            ->orderByDesc('ratings_avg_score')
            ->limit($limit)
            ->get();

        // 4. Fallback to highest-rated recipes if recommendations are sparse
        if ($recommended->count() < $limit) {
            $needed = $limit - $recommended->count();
            $excludeIds = $viewedOrSavedRecipeIds->merge($recommended->pluck('id'))->unique();

            $fallbacks = Recipe::where('is_approved', true)
                ->whereNotIn('id', $excludeIds)
                ->withAvg('ratings', 'score')
                ->orderByDesc('ratings_avg_score')
                ->limit($needed)
                ->get();

            $recommended = $recommended->concat($fallbacks);
        }

        return $recommended;
    }

    /**
     * Find recipes matching a list of ingredients (>= 50% match).
     */
    public function getByIngredients(array $ingredients): array
    {
        if (empty($ingredients)) {
            return [];
        }

        $ingredients = array_map(fn($i) => strtolower(trim($i)), $ingredients);
        $totalInput = count($ingredients);

        $localRecipes = Recipe::where('is_approved', true)->get();
        $matches = [];

        foreach ($localRecipes as $recipe) {
            $recipeIngredientsText = strtolower($recipe->ingredients);
            $matchedCount = 0;
            foreach ($ingredients as $ingredient) {
                if (stripos($recipeIngredientsText, $ingredient) !== false) {
                    $matchedCount++;
                }
            }

            $matchPercent = $totalInput > 0 ? ($matchedCount / $totalInput) * 100 : 0;
            if ($matchPercent >= 50) {
                $matches[] = [
                    'recipe' => $recipe,
                    'source' => 'local',
                    'matched_count' => $matchedCount,
                    'match_percent' => round($matchPercent, 1),
                ];
            }
        }

        // Sort by match_percent desc
        usort($matches, fn($a, $b) => $b['match_percent'] <=> $a['match_percent']);

        // Fallback to API if fewer than 3 local recipes match
        if (count($matches) < 3) {
            $apiMeals = [];
            $mealDbService = app(MealDbService::class);
            $apiCandidateIds = [];

            // Query using first 2 ingredient keywords
            $limitIngredients = array_slice($ingredients, 0, 2);
            foreach ($limitIngredients as $ing) {
                try {
                    $response = Http::timeout(5)->get('https://www.themealdb.com/api/json/v1/1/filter.php', [
                        'i' => $ing
                    ]);
                    if ($response->successful() && isset($response['meals']) && is_array($response['meals'])) {
                        foreach ($response['meals'] as $m) {
                            $apiCandidateIds[] = $m['idMeal'];
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('MealDB ingredient filter error: ' . $e->getMessage());
                }
            }

            $apiCandidateIds = array_unique($apiCandidateIds);
            $apiCandidateIds = array_slice($apiCandidateIds, 0, 10); // Limit API requests

            foreach ($apiCandidateIds as $mealId) {
                $meal = $mealDbService->findById($mealId);
                if ($meal) {
                    $mealIngredientsText = strtolower($meal['ingredients']);
                    $matchedCount = 0;
                    foreach ($ingredients as $ingredient) {
                        if (stripos($mealIngredientsText, $ingredient) !== false) {
                            $matchedCount++;
                        }
                    }
                    $matchPercent = $totalInput > 0 ? ($matchedCount / $totalInput) * 100 : 0;
                    if ($matchPercent >= 50) {
                        $apiMeals[] = [
                            'recipe' => (object)$meal,
                            'source' => 'api',
                            'matched_count' => $matchedCount,
                            'match_percent' => round($matchPercent, 1),
                        ];
                    }
                }
            }

            $matches = array_merge($matches, $apiMeals);
            usort($matches, fn($a, $b) => $b['match_percent'] <=> $a['match_percent']);
        }

        return $matches;
    }

    /**
     * Auto-tag a recipe based on its ingredients and instructions.
     */
    public function autoTagRecipe(string $ingredients, string $instructions): array
    {
        $text = strtolower($ingredients . ' ' . $instructions);
        $taxonomy = [
            'pedas' => ['pedas', 'cabe', 'cabai', 'sambal', 'lada', 'ricarica', 'rica'],
            'sehat' => ['sehat', 'sayur', 'buah', 'salad', 'diet', 'rebus', 'kukus', 'protein'],
            'cepat' => ['cepat', 'instant', 'instan', 'mudah', 'praktis', '10 menit', '15 menit', '5 menit'],
            'panggang' => ['panggang', 'oven', 'bakar', 'grill', 'roast'],
            'kukus' => ['kukus', 'steam'],
            'manis' => ['manis', 'gula', 'madu', 'cokelat', 'keju', 'susu', 'dessert'],
            'diet' => ['diet', 'low carb', 'keto', 'kalori rendah', 'sehat', 'tanpa minyak'],
            'goreng' => ['goreng', 'fry', 'tumis', 'minyak']
        ];

        $suggested = [];
        foreach ($taxonomy as $tag => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($text, $keyword) !== false) {
                    $suggested[] = $tag;
                    break;
                }
            }
        }

        return $suggested;
    }
}
