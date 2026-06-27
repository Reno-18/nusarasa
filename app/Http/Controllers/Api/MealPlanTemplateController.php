<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MealPlan;
use App\Models\MealPlanTemplate;
use App\Models\MealPlanTemplateItem;
use App\Services\MealDbService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MealPlanTemplateController extends Controller
{
    use ApiResponse;

    protected $mealDbService;

    public function __construct(MealDbService $mealDbService)
    {
        $this->mealDbService = $mealDbService;
    }

    /**
     * Display all meal plan templates.
     */
    public function index()
    {
        $templates = MealPlanTemplate::with(['items.recipe', 'chef'])->latest()->get();
        return $this->successResponse($templates, 'Templat rencana makan berhasil diambil');
    }

    /**
     * Store a new template (Chef only).
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isChef()) {
            return $this->errorResponse('Hanya Chef yang bisa membuat templat', 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'goal' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'items' => 'nullable|array',
        ]);

        $template = MealPlanTemplate::create([
            'chef_id' => auth()->id(),
            'name' => $request->name,
            'goal' => $request->goal,
            'description' => $request->description,
        ]);

        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $day => $times) {
                if (!is_array($times)) continue;
                foreach ($times as $time => $recipeValue) {
                    if (empty($recipeValue)) continue;

                    $recipeId = null;
                    $mealApiId = null;

                    if (str_starts_with($recipeValue, 'api_')) {
                        $mealApiId = str_replace('api_', '', $recipeValue);
                    } else {
                        $recipeId = (int)$recipeValue;
                    }

                    $template->items()->create([
                        'day_of_week' => $day,
                        'meal_time' => $time,
                        'recipe_id' => $recipeId,
                        'meal_api_id' => $mealApiId,
                    ]);
                }
            }
        }

        return $this->successResponse($template->load('items'), 'Templat rencana makan berhasil dibuat', 201);
    }

    /**
     * Update template (Chef only).
     */
    public function update(Request $request, $id)
    {
        $template = MealPlanTemplate::findOrFail($id);

        if ($template->chef_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return $this->errorResponse('Anda tidak berwenang memperbarui templat ini', 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'goal' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'items' => 'nullable|array',
        ]);

        $template->update([
            'name' => $request->name,
            'goal' => $request->goal,
            'description' => $request->description,
        ]);

        $template->items()->delete();

        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $day => $times) {
                if (!is_array($times)) continue;
                foreach ($times as $time => $recipeValue) {
                    if (empty($recipeValue)) continue;

                    $recipeId = null;
                    $mealApiId = null;

                    if (str_starts_with($recipeValue, 'api_')) {
                        $mealApiId = str_replace('api_', '', $recipeValue);
                    } else {
                        $recipeId = (int)$recipeValue;
                    }

                    $template->items()->create([
                        'day_of_week' => $day,
                        'meal_time' => $time,
                        'recipe_id' => $recipeId,
                        'meal_api_id' => $mealApiId,
                    ]);
                }
            }
        }

        return $this->successResponse($template->load('items'), 'Templat rencana makan berhasil diperbarui');
    }

    /**
     * Destroy template (Chef only).
     */
    public function destroy($id)
    {
        $template = MealPlanTemplate::findOrFail($id);

        if ($template->chef_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return $this->errorResponse('Anda tidak berwenang menghapus templat ini', 403);
        }

        $template->delete();
        return $this->successResponse(null, 'Templat rencana makan berhasil dihapus');
    }

    /**
     * Apply a meal template to the user's current week's meal plan.
     */
    public function apply($id)
    {
        $template = MealPlanTemplate::with('items')->findOrFail($id);
        $user = auth()->user();
        
        $weekStart = now()->startOfWeek()->format('Y-m-d');
        
        $mealPlan = MealPlan::firstOrCreate([
            'user_id' => $user->id,
            'week_start' => $weekStart,
        ]);
        
        $mealPlan->items()->delete();
        
        foreach ($template->items as $item) {
            // Normalize old 'camilan' key to 'snacks' for backward compatibility
            $mealType = $item->meal_time === 'camilan' ? 'snacks' : $item->meal_time;
            
            $apiTitle = null;
            $apiImage = null;
            if ($item->meal_api_id) {
                $meal = $this->mealDbService->findById($item->meal_api_id);
                if ($meal) {
                    $apiTitle = $meal['title'];
                    $apiImage = $meal['image_url'];
                }
            }
            
            $mealPlan->items()->create([
                'day_of_week' => $item->day_of_week,
                'meal_type' => $mealType,
                'recipe_id' => $item->recipe_id,
                'meal_api_id' => $item->meal_api_id,
                'meal_api_title' => $apiTitle,
                'meal_api_image' => $apiImage,
            ]);
        }
        
        return $this->successResponse($mealPlan->load('items.recipe'), 'Templat rencana makan berhasil diterapkan ke minggu ini');
    }
}
