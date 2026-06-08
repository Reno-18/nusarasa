<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Models\Recipe;
use App\Services\RecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserRecipeController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Show the recipe submission form (reuses the chef create view).
     */
    public function create(): View
    {
        $allTags = ['pedas', 'sehat', 'cepat', 'panggang', 'kukus', 'manis', 'diet', 'goreng'];
        return view('user.recipes.create', compact('allTags'));
    }

    /**
     * Store the submitted recipe (pending admin approval).
     */
    public function store(StoreRecipeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['source'] = 'local';
        $data['is_approved'] = false; // Always needs admin approval

        // Auto-tag if not manually provided
        if (!isset($data['tags'])) {
            $data['tags'] = $this->recommendationService->autoTagRecipe(
                $data['ingredients'],
                $data['instructions']
            );
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipes', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $recipe = Recipe::create($data);

        // Save tags to pivot table
        $recipe->recipeTags()->delete();
        if (!empty($data['tags']) && is_array($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                $recipe->recipeTags()->create(['tag' => strtolower(trim($tag))]);
            }
        }

        return redirect()->route('home')
            ->with('success', '✅ Resepmu berhasil dikirim! Resep akan ditampilkan setelah disetujui oleh admin.');
    }
}
