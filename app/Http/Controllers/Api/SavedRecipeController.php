<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SavedRecipe;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SavedRecipeController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $data = $request->validate([
            'recipe_id' => 'nullable|exists:recipes,id',
            'meal_api_id' => 'nullable|string',
            'meal_api_title' => 'nullable|string',
            'meal_api_image' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();

        try {
            $savedRecipe = SavedRecipe::create($data);

            return $this->successResponse($savedRecipe, 'Resep berhasil disimpan ke favorit', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Resep sudah ada di favorit Anda', 422);
        }
    }

    public function destroy($id)
    {
        $savedRecipe = SavedRecipe::where('user_id', auth()->id())
            ->findOrFail($id);

        $savedRecipe->delete();

        return $this->successResponse(null, 'Recipe removed from saved list');
    }
}
