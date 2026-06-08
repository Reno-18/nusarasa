<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChefRecipeController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display a listing of recipes by this chef.
     */
    public function index()
    {
        $recipes = Recipe::where('user_id', auth()->id())->latest()->get();
        return view('chef.recipes.index', compact('recipes'));
    }

    /**
     * Show the form for creating a new recipe.
     */
    public function create()
    {
        $allTags = ['pedas', 'sehat', 'cepat', 'panggang', 'kukus', 'manis', 'diet', 'goreng'];
        return view('chef.recipes.create', compact('allTags'));
    }

    /**
     * Store a newly created recipe in storage.
     */
    public function store(StoreRecipeRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['source'] = 'local';
        $data['is_approved'] = false;

        // Auto-determine tags if not supplied
        if (!isset($data['tags'])) {
            $data['tags'] = $this->recommendationService->autoTagRecipe($data['ingredients'], $data['instructions']);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipes', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $recipe = Recipe::create($data);

        // Save to pivot table
        $this->saveRecipeTags($recipe, $data['tags']);

        return redirect()->route('chef.dashboard')->with('success', 'Recipe created successfully. Waiting for admin approval.');
    }

    /**
     * Show the form for editing the specified recipe.
     */
    public function edit($id)
    {
        $recipe = Recipe::where('user_id', auth()->id())->findOrFail($id);
        $allTags = ['pedas', 'sehat', 'cepat', 'panggang', 'kukus', 'manis', 'diet', 'goreng'];
        
        return view('chef.recipes.edit', compact('recipe', 'allTags'));
    }

    /**
     * Update the specified recipe in storage.
     */
    public function update(UpdateRecipeRequest $request, $id)
    {
        $recipe = Recipe::where('user_id', auth()->id())->findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($recipe->image_url) {
                $oldPath = str_replace('/storage/', '', $recipe->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('recipes', 'public');
            $data['image_url'] = Storage::url($path);
        }

        // Auto-determine tags if not supplied
        if (!isset($data['tags'])) {
            $data['tags'] = $this->recommendationService->autoTagRecipe($data['ingredients'], $data['instructions']);
        }

        $recipe->update($data);

        // Save to pivot table
        $this->saveRecipeTags($recipe, $data['tags']);

        return redirect()->route('chef.dashboard')->with('success', 'Recipe updated successfully');
    }

    /**
     * Delete the recipe.
     */
    public function destroy($id)
    {
        $recipe = Recipe::where('user_id', auth()->id())->findOrFail($id);

        if ($recipe->image_url) {
            $oldPath = str_replace('/storage/', '', $recipe->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $recipe->delete();

        return redirect()->route('chef.dashboard')->with('success', 'Recipe deleted successfully');
    }

    /**
     * Helper to save tags to pivot table.
     */
    protected function saveRecipeTags(Recipe $recipe, ?array $tags)
    {
        $recipe->recipeTags()->delete();

        if ($tags && is_array($tags)) {
            foreach ($tags as $tag) {
                $recipe->recipeTags()->create([
                    'tag' => strtolower(trim($tag))
                ]);
            }
        }
    }
}
