<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecipeIngredientController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display the search by ingredients page and process search requests.
     */
    public function index(Request $request)
    {
        $ingredientsInput = $request->get('ingredients', '');
        $results = [];
        $ingredients = [];

        if (!empty($ingredientsInput)) {
            $ingredients = array_filter(array_map('trim', explode(',', $ingredientsInput)));
            if (!empty($ingredients)) {
                $results = $this->recommendationService->getByIngredients($ingredients);
            }
        }

        return view('recipes.by-ingredients', [
            'results' => $results,
            'ingredients' => $ingredients,
            'ingredientsInput' => $ingredientsInput
        ]);
    }
}
