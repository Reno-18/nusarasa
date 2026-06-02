<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\MealPlanResource;
use App\Models\MealPlan;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MealPlanController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $mealPlans = MealPlan::where('user_id', auth()->id())
            ->with('items.recipe')
            ->orderBy('week_start', 'desc')
            ->get();

        return $this->successResponse(
            MealPlanResource::collection($mealPlans),
            'Meal plans retrieved successfully'
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'week_start' => 'required|date',
            'day_of_week' => 'nullable|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'meal_type' => 'nullable|string|in:breakfast,lunch,dinner',
            'recipe_id' => 'nullable|exists:recipes,id',
            'meal_api_id' => 'nullable|string',
            'meal_api_title' => 'nullable|string',
            'meal_api_image' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.day_of_week' => 'required_with:items|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'items.*.meal_type' => 'required_with:items|in:breakfast,lunch,dinner',
            'items.*.recipe_id' => 'nullable|exists:recipes,id',
            'items.*.meal_api_id' => 'nullable|string',
            'items.*.meal_api_title' => 'nullable|string',
            'items.*.meal_api_image' => 'nullable|string',
        ]);

        $userId = auth()->id();
        
        // Find or create meal plan for this week
        $mealPlan = MealPlan::firstOrCreate([
            'user_id' => $userId,
            'week_start' => $data['week_start'],
        ]);

        // Add single item if provided
        if (isset($data['day_of_week']) && isset($data['meal_type'])) {
            $mealPlan->items()->create([
                'day_of_week' => $data['day_of_week'],
                'meal_type' => $data['meal_type'],
                'recipe_id' => $data['recipe_id'] ?? null,
                'meal_api_id' => $data['meal_api_id'] ?? null,
                'meal_api_title' => $data['meal_api_title'] ?? null,
                'meal_api_image' => $data['meal_api_image'] ?? null,
            ]);
        }

        // Add multiple items if provided
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $mealPlan->items()->create($item);
            }
        }

        $mealPlan->load('items.recipe');

        return $this->successResponse(
            new MealPlanResource($mealPlan),
            'Rencana makan berhasil diperbarui',
            201
        );
    }

    public function show($id)
    {
        $mealPlan = MealPlan::where('user_id', auth()->id())
            ->with('items.recipe')
            ->findOrFail($id);

        return $this->successResponse(
            new MealPlanResource($mealPlan),
            'Meal plan retrieved successfully'
        );
    }

    public function update(Request $request, $id)
    {
        $mealPlan = MealPlan::where('user_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'week_start' => 'sometimes|date',
            'items' => 'sometimes|array',
            'items.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'items.*.meal_type' => 'required|in:breakfast,lunch,dinner',
            'items.*.recipe_id' => 'nullable|exists:recipes,id',
            'items.*.meal_api_id' => 'nullable|string',
            'items.*.meal_api_title' => 'nullable|string',
            'items.*.meal_api_image' => 'nullable|string',
        ]);

        if (isset($data['week_start'])) {
            $mealPlan->update(['week_start' => $data['week_start']]);
        }

        if (isset($data['items'])) {
            $mealPlan->items()->delete();
            foreach ($data['items'] as $item) {
                $mealPlan->items()->create($item);
            }
        }

        $mealPlan->load('items.recipe');

        return $this->successResponse(
            new MealPlanResource($mealPlan),
            'Meal plan updated successfully'
        );
    }

    public function destroy($id)
    {
        $mealPlan = MealPlan::where('user_id', auth()->id())->findOrFail($id);
        $mealPlan->delete();

        return $this->successResponse(null, 'Meal plan deleted successfully');
    }

    public function destroyItem($id)
    {
        try {
            $userId = auth()->id();
            if (!$userId) {
                return $this->errorResponse('Unauthorized', 401);
            }

            $mealPlanIds = MealPlan::where('user_id', $userId)->pluck('id');
            
            $item = \App\Models\MealPlanItem::whereIn('meal_plan_id', $mealPlanIds)
                ->findOrFail($id);
                
            $item->delete();

            return $this->successResponse(null, 'Menu berhasil dihapus dari rencana makan');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('MealPlanItem deletion error: ' . $e->getMessage());
            return $this->errorResponse('Gagal menghapus menu', 500);
        }
    }
}
