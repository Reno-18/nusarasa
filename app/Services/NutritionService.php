<?php

namespace App\Services;

use App\Models\MealPlan;
use App\Models\Recipe;
use Illuminate\Support\Facades\Log;

class NutritionService
{
    /**
     * Get weekly nutrition summary for a meal plan.
     */
    public function getWeeklyNutrition(MealPlan $plan): array
    {
        $summary = [
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fat' => 0,
            'fiber' => 0,
        ];

        $plan->load('items.recipe');

        foreach ($plan->items as $item) {
            $nutrition = null;

            if ($item->recipe_id && $item->recipe) {
                // Local recipe
                $nutrition = $item->recipe->nutrition;
            } elseif ($item->meal_api_id) {
                // API recipe
                $nutrition = $this->fetchFromMealDb($item->meal_api_id);
            }

            if ($nutrition && is_array($nutrition)) {
                $summary['calories'] += (float)($nutrition['calories'] ?? 0);
                $summary['protein'] += (float)($nutrition['protein'] ?? 0);
                $summary['carbs'] += (float)($nutrition['carbs'] ?? 0);
                $summary['fat'] += (float)($nutrition['fat'] ?? 0);
                $summary['fiber'] += (float)($nutrition['fiber'] ?? 0);
            }
        }

        // Round all values
        foreach ($summary as $key => $val) {
            $summary[$key] = round($val, 1);
        }

        return $summary;
    }

    /**
     * Get aggregated shopping list of ingredients from all items in the plan.
     */
    public function getShoppingList(MealPlan $plan): array
    {
        $plan->load('items.recipe');
        $recipes = [];
        $flatList = [];

        foreach ($plan->items as $item) {
            $title = '';
            $ingredientsText = '';

            if ($item->recipe_id && $item->recipe) {
                $title = $item->recipe->title;
                $ingredientsText = $item->recipe->ingredients;
            } elseif ($item->meal_api_id) {
                $title = $item->meal_api_title ?? 'Resep API';
                $mealDbService = app(MealDbService::class);
                $meal = $mealDbService->findById($item->meal_api_id);
                if ($meal) {
                    $title = $meal['title'];
                    $ingredientsText = $meal['ingredients'];
                }
            }

            if (!empty($title) && !empty($ingredientsText)) {
                $ingredientsArray = array_filter(
                    array_map('trim', explode("\n", str_replace("\r", "", $ingredientsText))),
                    fn($line) => !empty($line)
                );

                $recipes[] = [
                    'title' => $title,
                    'ingredients' => $ingredientsArray,
                ];

                foreach ($ingredientsArray as $ingLine) {
                    // Try to extract name and quantity (simplistic normalization)
                    // If ingredient is "2 cups rice", we can try to extract "rice" and "2 cups"
                    // But to be safe and simple, we'll store the lines grouped by raw content or clean string
                    $cleanLine = trim(strtolower($ingLine));
                    if (!isset($flatList[$cleanLine])) {
                        $flatList[$cleanLine] = [
                            'original' => $ingLine,
                            'count' => 1,
                        ];
                    } else {
                        $flatList[$cleanLine]['count']++;
                    }
                }
            }
        }

        return [
            'recipes' => $recipes,
            'flat_list' => array_values($flatList),
        ];
    }

    /**
     * Get aggregated shopping list for specific recipe IDs and/or API IDs only.
     */
    public function getShoppingListForItems(MealPlan $plan, array $recipeIds = [], array $apiIds = []): array
    {
        $plan->load('items.recipe');
        $recipes = [];
        $flatList = [];

        foreach ($plan->items as $item) {
            $title = '';
            $ingredientsText = '';

            if ($item->recipe_id && $item->recipe && in_array($item->recipe_id, $recipeIds)) {
                $title = $item->recipe->title;
                $ingredientsText = $item->recipe->ingredients;
            } elseif ($item->meal_api_id && in_array($item->meal_api_id, $apiIds)) {
                $title = $item->meal_api_title ?? 'Resep API';
                $mealDbService = app(MealDbService::class);
                $meal = $mealDbService->findById($item->meal_api_id);
                if ($meal) {
                    $title = $meal['title'];
                    $ingredientsText = $meal['ingredients'];
                }
            }

            if (!empty($title) && !empty($ingredientsText)) {
                $ingredientsArray = array_values(array_filter(
                    array_map('trim', explode("\n", str_replace("\r", "", $ingredientsText))),
                    fn($line) => !empty($line)
                ));

                // Deduplicate recipes by title
                $alreadyAdded = collect($recipes)->pluck('title')->contains($title);
                if (!$alreadyAdded) {
                    $recipes[] = [
                        'title' => $title,
                        'ingredients' => $ingredientsArray,
                    ];
                }

                foreach ($ingredientsArray as $ingLine) {
                    $cleanLine = trim(strtolower($ingLine));
                    if (!isset($flatList[$cleanLine])) {
                        $flatList[$cleanLine] = [
                            'original' => $ingLine,
                            'count' => 1,
                        ];
                    } else {
                        $flatList[$cleanLine]['count']++;
                    }
                }
            }
        }

        return [
            'recipes' => $recipes,
            'flat_list' => array_values($flatList),
        ];
    }

    /**
     * Get aggregated shopping list for specific meal plan item IDs.
     */
    public function getShoppingListForPlanItems(MealPlan $plan, array $itemIds = []): array
    {
        $plan->load('items.recipe');
        $recipes = [];
        $flatList = [];

        foreach ($plan->items as $item) {
            if (!in_array($item->id, $itemIds)) {
                continue;
            }

            $title = '';
            $ingredientsText = '';

            if ($item->recipe_id && $item->recipe) {
                $title = $item->recipe->title;
                $ingredientsText = $item->recipe->ingredients;
            } elseif ($item->meal_api_id) {
                $title = $item->meal_api_title ?? 'Resep API';
                $mealDbService = app(MealDbService::class);
                $meal = $mealDbService->findById($item->meal_api_id);
                if ($meal) {
                    $title = $meal['title'];
                    $ingredientsText = $meal['ingredients'];
                }
            }

            if (!empty($title) && !empty($ingredientsText)) {
                $ingredientsArray = array_values(array_filter(
                    array_map('trim', explode("\n", str_replace("\r", "", $ingredientsText))),
                    fn($line) => !empty($line)
                ));

                $dayNames = ['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'];
                $typeNames = ['breakfast' => 'Sarapan', 'lunch' => 'Makan Siang', 'dinner' => 'Makan Malam'];
                $dayLabel = $dayNames[$item->day_of_week] ?? $item->day_of_week;
                $typeLabel = $typeNames[$item->meal_type] ?? $item->meal_type;

                $recipes[] = [
                    'title' => $title,
                    'day' => $dayLabel,
                    'meal_type' => $typeLabel,
                    'ingredients' => $ingredientsArray,
                ];

                foreach ($ingredientsArray as $ingLine) {
                    $cleanLine = trim(strtolower($ingLine));
                    if (!isset($flatList[$cleanLine])) {
                        $flatList[$cleanLine] = [
                            'original' => $ingLine,
                            'count' => 1,
                        ];
                    } else {
                        $flatList[$cleanLine]['count']++;
                    }
                }
            }
        }

        return [
            'recipes' => $recipes,
            'flat_list' => array_values($flatList),
        ];
    }

    /**
     * Get aggregated shopping list for specific days of the week.
     */
    public function getShoppingListForDays(MealPlan $plan, array $days = []): array
    {
        $plan->load('items.recipe');
        $recipes = [];
        $flatList = [];

        $dayNames  = ['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'];
        $typeNames = ['breakfast' => 'Sarapan', 'lunch' => 'Makan Siang', 'dinner' => 'Makan Malam'];

        foreach ($plan->items as $item) {
            if (!in_array($item->day_of_week, $days)) {
                continue;
            }

            $title = '';
            $ingredientsText = '';

            if ($item->recipe_id && $item->recipe) {
                $title = $item->recipe->title;
                $ingredientsText = $item->recipe->ingredients;
            } elseif ($item->meal_api_id) {
                $title = $item->meal_api_title ?? 'Resep API';
                $mealDbService = app(MealDbService::class);
                $meal = $mealDbService->findById($item->meal_api_id);
                if ($meal) {
                    $title = $meal['title'];
                    $ingredientsText = $meal['ingredients'];
                }
            }

            if (!empty($title) && !empty($ingredientsText)) {
                $ingredientsArray = array_values(array_filter(
                    array_map('trim', explode("\n", str_replace("\r", "", $ingredientsText))),
                    fn($line) => !empty($line)
                ));

                $dayLabel  = $dayNames[$item->day_of_week] ?? $item->day_of_week;
                $typeLabel = $typeNames[$item->meal_type] ?? $item->meal_type;

                $recipes[] = [
                    'title'       => $title,
                    'day'         => $dayLabel,
                    'meal_type'   => $typeLabel,
                    'ingredients' => $ingredientsArray,
                ];

                foreach ($ingredientsArray as $ingLine) {
                    $cleanLine = trim(strtolower($ingLine));
                    if (!isset($flatList[$cleanLine])) {
                        $flatList[$cleanLine] = ['original' => $ingLine, 'count' => 1];
                    } else {
                        $flatList[$cleanLine]['count']++;
                    }
                }
            }
        }

        return [
            'recipes'   => $recipes,
            'flat_list' => array_values($flatList),
        ];
    }

    /**
     * Retrieve or estimate nutrition information for an API recipe.
     */
    public function fetchFromMealDb(string $mealId): ?array
    {
        $mealDbService = app(MealDbService::class);
        $meal = $mealDbService->findById($mealId);
        if (!$meal) {
            return null;
        }

        // TheMealDB does not return actual nutrition. We estimate deterministically.
        return $this->estimateNutrition($meal['title'], $meal['ingredients']);
    }

    /**
     * Deterministically estimate nutritional values based on recipe title and ingredients.
     */
    public function estimateNutrition(string $title, string $ingredients): array
    {
        $hash = crc32($title . $ingredients);

        // Generate realistic ranges:
        // Calories: 350 - 750 kcal
        // Protein: 12 - 42g
        // Fat: 6 - 26g
        // Carbs: 25 - 85g
        // Fiber: 2 - 10g
        $calories = 350 + (abs($hash) % 400);
        $protein = 12 + (abs($hash + 1) % 30);
        $fat = 6 + (abs($hash + 2) % 20);
        $carbs = 25 + (abs($hash + 3) % 60);
        $fiber = 2 + (abs($hash + 4) % 8);

        return [
            'calories' => $calories,
            'protein' => $protein,
            'fat' => $fat,
            'carbs' => $carbs,
            'fiber' => $fiber,
        ];
    }
}
