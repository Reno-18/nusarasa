<?php

namespace App\Http\Controllers\Chef;

use App\Http\Controllers\Controller;
use App\Models\MealPlanTemplate;
use App\Models\Recipe;
use App\Services\MealDbService;
use Illuminate\Http\Request;

class MealPlanTemplateController extends Controller
{
    protected $mealDbService;

    public function __construct(MealDbService $mealDbService)
    {
        $this->mealDbService = $mealDbService;
    }

    /**
     * Display a listing of meal templates created by this chef.
     */
    public function index()
    {
        $templates = MealPlanTemplate::where('chef_id', auth()->id())
            ->with('items.recipe')
            ->latest()
            ->paginate(10);

        return view('chef.meal-plan-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new meal template.
     */
    public function create()
    {
        $recipes = Recipe::where('is_approved', true)->orderBy('title')->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $mealTimes = ['breakfast', 'lunch', 'dinner', 'camilan'];

        return view('chef.meal-plan-templates.create', compact('recipes', 'days', 'mealTimes'));
    }

    /**
     * Store a newly created meal template in storage.
     */
    public function store(Request $request)
    {
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

        return redirect()->route('chef.meal-plan-templates.index')
            ->with('success', 'Templat rencana makan berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified meal template.
     */
    public function edit($id)
    {
        $template = MealPlanTemplate::where('chef_id', auth()->id())
            ->with('items')
            ->findOrFail($id);

        $recipes = Recipe::where('is_approved', true)->orderBy('title')->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $mealTimes = ['breakfast', 'lunch', 'dinner', 'camilan'];

        $groupedItems = [];
        foreach ($template->items as $item) {
            $val = $item->recipe_id ? (string)$item->recipe_id : 'api_' . $item->meal_api_id;
            $groupedItems[$item->day_of_week][$item->meal_time] = $val;
        }

        return view('chef.meal-plan-templates.edit', compact('template', 'recipes', 'days', 'mealTimes', 'groupedItems'));
    }

    /**
     * Update the specified meal template in storage.
     */
    public function update(Request $request, $id)
    {
        $template = MealPlanTemplate::where('chef_id', auth()->id())->findOrFail($id);

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

        return redirect()->route('chef.meal-plan-templates.index')
            ->with('success', 'Templat rencana makan berhasil diperbarui!');
    }

    /**
     * Remove the specified meal template from storage.
     */
    public function destroy($id)
    {
        $template = MealPlanTemplate::where('chef_id', auth()->id())->findOrFail($id);
        $template->delete();

        return redirect()->route('chef.meal-plan-templates.index')
            ->with('success', 'Templat rencana makan berhasil dihapus!');
    }
}
