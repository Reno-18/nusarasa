<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Services\MealDbService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    use ApiResponse;

    protected $mealDbService;

    public function __construct(MealDbService $mealDbService)
    {
        $this->mealDbService = $mealDbService;
    }

    public function index(Request $request)
    {
        $query = Recipe::where('is_approved', true)->with('user')->withAvg('ratings', 'score');

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ingredients', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        $sort = $request->get('sort', 'all');
        $page = (int) $request->get('page', 1);
        $perPage = 12;

        $localRecipes = [];
        // 1. Fetch Local Recipes (unless sort is explicitly 'api')
        if ($sort !== 'api') {
            // Get all local matching recipes
            $localRecipes = $query->get()->all();
        }

        $apiRecipes = [];
        // 2. Fetch API Recipes (unless sort is explicitly 'local')
        if ($sort !== 'local') {
            if ($request->has('category') && !empty($request->category)) {
                $apiRecipes = $this->mealDbService->filterByCategory($request->category);
            } else {
                // If no category, search with query or default empty string ''
                $searchQuery = $request->get('search') ?? '';
                $apiRecipes = $this->mealDbService->search($searchQuery);
            }
        }

        $recipes = array_merge($localRecipes, $apiRecipes);

        // 3. Optional: Shuffle or sort them if needed. 
        if ($sort === 'all') {
            // Seed the random generator with search/category so the shuffle is consistent across pages
            $seed = md5(($request->get('search') ?? '') . ($request->get('category') ?? ''));
            mt_srand(crc32($seed));
            shuffle($recipes);
            mt_srand(); // reset seed
        }

        // 4. Manually slice the merged array for pagination
        $total = count($recipes);
        $pagedRecipes = array_slice($recipes, ($page - 1) * $perPage, $perPage);
        $lastPage = ceil($total / $perPage);

        return $this->successResponse([
            'recipes' => array_values($pagedRecipes),
            'pagination' => [
                'current_page' => $page,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total,
            ]
        ], 'Recipes retrieved successfully');
    }

    public function show($id)
    {
        $recipe = Recipe::with('user', 'ratings.user')->find($id);

        if (!$recipe) {
            return $this->errorResponse('Recipe not found', 404);
        }

        // Increment view count
        $recipe->increment('view_count');

        if (auth('sanctum')->check()) {
            auth('sanctum')->user()->recipeViews()->create([
                'recipe_id' => $id,
                'viewed_at' => now(),
            ]);
        }

        return $this->successResponse(new RecipeResource($recipe), 'Recipe retrieved successfully');
    }

    public function store(StoreRecipeRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['source'] = 'local';
        $data['is_approved'] = false;

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipes', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $recipe = Recipe::create($data);

        return $this->successResponse(new RecipeResource($recipe), 'Recipe created successfully', 201);
    }

    public function update(UpdateRecipeRequest $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $this->authorize('update', $recipe);

        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($recipe->image_url) {
                $oldPath = str_replace('/storage/', '', $recipe->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('recipes', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $recipe->update($data);

        return $this->successResponse(new RecipeResource($recipe), 'Recipe updated successfully');
    }

    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);

        $this->authorize('delete', $recipe);

        // Delete image if exists
        if ($recipe->image_url) {
            $oldPath = str_replace('/storage/', '', $recipe->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $recipe->delete();

        return $this->successResponse(null, 'Recipe deleted successfully');
    }

    public function approve($id)
    {
        $recipe = Recipe::findOrFail($id);

        $this->authorize('approve', Recipe::class);

        $recipe->update(['is_approved' => true]);

        return $this->successResponse(new RecipeResource($recipe), 'Recipe approved successfully');
    }
}
