<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RecommendationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    use ApiResponse;

    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Get personalized recommendations for the authenticated user.
     */
    public function getRecommendations()
    {
        $user = auth()->user();
        $recommendations = $this->recommendationService->getRecommendations($user);
        
        return $this->successResponse($recommendations, 'Rekomendasi resep berhasil diambil');
    }

    /**
     * Search recipes by ingredients.
     */
    public function getByIngredients(Request $request)
    {
        $ingredientsInput = $request->input('ingredients');
        if (empty($ingredientsInput)) {
            return $this->errorResponse('Parameter ingredients diperlukan', 400);
        }

        $ingredients = is_array($ingredientsInput) 
            ? $ingredientsInput 
            : array_filter(array_map('trim', explode(',', $ingredientsInput)));

        $results = $this->recommendationService->getByIngredients($ingredients);

        return $this->successResponse($results, 'Hasil pencarian berdasarkan bahan masakan');
    }

    /**
     * Get suggested auto-tags based on ingredients and instructions.
     */
    public function getAutoTags(Request $request)
    {
        $request->validate([
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
        ]);

        $tags = $this->recommendationService->autoTagRecipe(
            $request->input('ingredients'),
            $request->input('instructions')
        );

        return $this->successResponse($tags, 'Auto-tags berhasil direkomendasikan');
    }
}
