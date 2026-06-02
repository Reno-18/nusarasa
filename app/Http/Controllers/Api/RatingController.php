<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use App\Models\Recipe;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    use ApiResponse;

    public function index($recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);
        $ratings = $recipe->ratings()->with('user')->latest()->get();

        $averageRating = $ratings->avg('score');

        return $this->successResponse([
            'ratings' => RatingResource::collection($ratings),
            'average_rating' => round($averageRating, 1),
            'total_ratings' => $ratings->count(),
        ], 'Ratings retrieved successfully');
    }

    public function store(StoreRatingRequest $request, $recipeId)
    {
        $recipe = Recipe::findOrFail($recipeId);

        // Check if user already rated this recipe
        $existingRating = Rating::where('user_id', auth()->id())
            ->where('recipe_id', $recipeId)
            ->first();

        if ($existingRating) {
            return $this->errorResponse('Anda sudah memberikan ulasan untuk resep ini', 422);
        }

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['recipe_id'] = $recipeId;

        $rating = Rating::create($data);
        $rating->load('user');

        return $this->successResponse(
            new RatingResource($rating),
            'Ulasan berhasil ditambahkan',
            201
        );
    }
}
