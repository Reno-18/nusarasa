<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MealDbService
{
    private string $baseUrl = 'https://www.themealdb.com/api/json/v1/1/';

    public function search(string $query): array
    {
        $cacheKey = 'mealdb_search_' . md5($query);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($query) {
            try {
                $response = Http::timeout(5)->get($this->baseUrl . 'search.php', [
                    's' => $query
                ]);

                if ($response->successful() && isset($response['meals'])) {
                    return array_map(fn($meal) => $this->normalizeMeal($meal), $response['meals']);
                }

                return [];
            } catch (\Exception $e) {
                Log::error('MealDB search error: ' . $e->getMessage());
                return [];
            }
        });
    }

    public function findById(string $mealId): ?array
    {
        $cacheKey = 'mealdb_lookup_' . $mealId;

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($mealId) {
            try {
                $response = Http::timeout(5)->get($this->baseUrl . 'lookup.php', [
                    'i' => $mealId
                ]);

                if ($response->successful() && isset($response['meals'][0])) {
                    return $this->normalizeMeal($response['meals'][0]);
                }

                return null;
            } catch (\Exception $e) {
                Log::error('MealDB findById error: ' . $e->getMessage());
                return null;
            }
        });
    }

    public function getCategories(): array
    {
        return Cache::remember('mealdb_categories', now()->addHours(24), function () {
            try {
                $response = Http::timeout(5)->get($this->baseUrl . 'categories.php');

                if ($response->successful() && isset($response['categories'])) {
                    return array_map(fn($cat) => $cat['strCategory'], $response['categories']);
                }

                return [];
            } catch (\Exception $e) {
                Log::error('MealDB getCategories error: ' . $e->getMessage());
                return [];
            }
        });
    }

    public function filterByCategory(string $category): array
    {
        $cacheKey = 'mealdb_filter_' . md5($category);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($category) {
            try {
                $response = Http::timeout(5)->get($this->baseUrl . 'filter.php', [
                    'c' => $category
                ]);

                if ($response->successful() && isset($response['meals'])) {
                    // Filter endpoint returns limited data, so we need to fetch full details
                    return array_map(function($meal) {
                        return [
                            'id' => $meal['idMeal'],
                            'title' => $meal['strMeal'],
                            'image_url' => $meal['strMealThumb'],
                            'category' => null,
                            'origin' => null,
                            'ingredients' => null,
                            'instructions' => null,
                            'source' => 'api'
                        ];
                    }, $response['meals']);
                }

                return [];
            } catch (\Exception $e) {
                Log::error('MealDB filterByCategory error: ' . $e->getMessage());
                return [];
            }
        });
    }

    private function normalizeMeal(array $meal): array
    {
        $ingredients = [];
        for ($i = 1; $i <= 20; $i++) {
            $ingredient = $meal["strIngredient{$i}"] ?? '';
            $measure = $meal["strMeasure{$i}"] ?? '';
            
            if (!empty($ingredient)) {
                $ingredients[] = trim($measure . ' ' . $ingredient);
            }
        }

        return [
            'id' => $meal['idMeal'],
            'title' => $meal['strMeal'],
            'category' => $meal['strCategory'] ?? null,
            'origin' => $meal['strArea'] ?? null,
            'image_url' => $meal['strMealThumb'] ?? null,
            'ingredients' => implode("\n", $ingredients),
            'instructions' => $meal['strInstructions'] ?? '',
            'source' => 'api'
        ];
    }
}
