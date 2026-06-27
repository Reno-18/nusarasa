<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Models\MealPlanItem;
use App\Models\MealPlanTemplate;
use App\Models\MealPlanStreak;
use App\Models\Recipe;
use App\Services\NutritionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MealPlanWebController extends Controller
{
    protected $nutritionService;

    public function __construct(NutritionService $nutritionService)
    {
        $this->nutritionService = $nutritionService;
    }

    /**
     * Display the meal plan dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        $mealPlan = MealPlan::where('user_id', $user->id)
            ->with('items.recipe')
            ->latest()
            ->first();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner', 'snacks'];

        // Get nutrition summary
        $nutritionSummary = $mealPlan ? $this->nutritionService->getWeeklyNutrition($mealPlan) : [
            'calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0, 'fiber' => 0
        ];

        // Get streak
        $streak = $user->streak;
        if (!$streak) {
            $streak = MealPlanStreak::firstOrCreate(
                ['user_id' => $user->id],
                ['current_streak' => 0, 'longest_streak' => 0]
            );
        }

        // Fetch templates
        $templates = MealPlanTemplate::with('items.recipe', 'chef')->get();

        return view('meal-plan.index', compact('mealPlan', 'days', 'mealTypes', 'nutritionSummary', 'streak', 'templates'));
    }

    /**
     * Display all available meal plan templates for users.
     */
    public function templates()
    {
        $templates = MealPlanTemplate::with('items', 'chef')
            ->latest()
            ->paginate(12);

        return view('meal-plan.templates', compact('templates'));
    }

    /**
     * Step 1 — Show day-based selection form for the shopping list.
     */
    public function shoppingList()
    {
        $currentWeekStart = now()->startOfWeek()->toDateString();
        $mealPlan = MealPlan::where('user_id', auth()->id())
            ->where('week_start', $currentWeekStart)
            ->with('items.recipe')
            ->first();

        if (!$mealPlan) {
            $mealPlan = MealPlan::where('user_id', auth()->id())
                ->with('items.recipe')
                ->latest()
                ->first();
        }

        if (!$mealPlan) {
            return redirect()->route('meal-plan.index')->with('error', 'Buat rencana makan terlebih dahulu!');
        }

        // Build day-grouped plan — only days that have at least one item
        $dayOrder  = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $dayNames  = ['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'];
        $typeNames = ['breakfast' => 'Sarapan', 'lunch' => 'Makan Siang', 'dinner' => 'Makan Malam'];
        $typeEmojis = ['breakfast' => '🍳', 'lunch' => '🍱', 'dinner' => '🍲'];
        $dayColors = ['monday' => 'bg-nusarasa-pink', 'tuesday' => 'bg-nusarasa-purple', 'wednesday' => 'bg-nusarasa-yellow', 'thursday' => 'bg-emerald-100', 'friday' => 'bg-orange-100', 'saturday' => 'bg-blue-100', 'sunday' => 'bg-red-100'];

        $dayPlans = [];
        foreach ($mealPlan->items as $item) {
            $day = $item->day_of_week;
            if (!isset($dayPlans[$day])) {
                $dayPlans[$day] = [
                    'day'   => $day,
                    'label' => $dayNames[$day] ?? $day,
                    'color' => $dayColors[$day] ?? 'bg-white',
                    'items' => [],
                ];
            }
            $dayPlans[$day]['items'][] = [
                'id'         => $item->id,
                'title'      => $item->recipe_id ? ($item->recipe->title ?? 'Resep') : ($item->meal_api_title ?? 'Resep API'),
                'image_url'  => $item->recipe_id ? ($item->recipe->image_url ?? null) : ($item->meal_api_image ?? null),
                'meal_type'  => $typeNames[$item->meal_type] ?? $item->meal_type,
                'meal_emoji' => $typeEmojis[$item->meal_type] ?? '🍽️',
            ];
        }

        // Sort by weekday order
        uksort($dayPlans, fn($a, $b) => array_search($a, $dayOrder) - array_search($b, $dayOrder));

        return view('meal-plan.shopping-list', compact('mealPlan', 'dayPlans'));
    }

    /**
     * Step 2 — Generate the shopping list for the selected days.
     */
    public function generateShoppingList(Request $request)
    {
        $currentWeekStart = now()->startOfWeek()->toDateString();
        $mealPlan = MealPlan::where('user_id', auth()->id())
            ->where('week_start', $currentWeekStart)
            ->with('items.recipe')
            ->first();

        if (!$mealPlan) {
            $mealPlan = MealPlan::where('user_id', auth()->id())
                ->with('items.recipe')
                ->latest()
                ->first();
        }

        if (!$mealPlan) {
            return redirect()->route('meal-plan.index')->with('error', 'Buat rencana makan terlebih dahulu!');
        }

        $selectedDays = $request->input('days', []);

        if (empty($selectedDays)) {
            return back()->with('error', 'Pilih minimal satu hari untuk daftar belanja.');
        }

        $shoppingList = $this->nutritionService->getShoppingListForDays($mealPlan, $selectedDays);

        return view('meal-plan.shopping-list-result', compact('shoppingList', 'mealPlan', 'selectedDays'));
    }

    /**
     * Step 3 — Download the shopping list as a PDF.
     */
    public function downloadPdf(Request $request)
    {
        $currentWeekStart = now()->startOfWeek()->toDateString();
        $mealPlan = MealPlan::where('user_id', auth()->id())
            ->where('week_start', $currentWeekStart)
            ->with('items.recipe')
            ->first();

        if (!$mealPlan) {
            $mealPlan = MealPlan::where('user_id', auth()->id())
                ->with('items.recipe')
                ->latest()
                ->first();
        }

        if (!$mealPlan) {
            return redirect()->route('meal-plan.index')->with('error', 'Buat rencana makan terlebih dahulu!');
        }

        $selectedDays = $request->input('days', []);

        if (empty($selectedDays)) {
            return back()->with('error', 'Pilih minimal satu hari untuk di-download.');
        }

        $shoppingList = $this->nutritionService->getShoppingListForDays($mealPlan, $selectedDays);

        $pdf = Pdf::loadView('meal-plan.pdf', compact('shoppingList', 'mealPlan', 'selectedDays'));
        return $pdf->download('nusarasa_daftar_belanja.pdf');
    }

    /**
     * Delete a meal plan item.
     */
    public function destroyItem($id)
    {
        try {
            $mealPlanIds = MealPlan::where('user_id', auth()->id())->pluck('id');
            $item = MealPlanItem::whereIn('meal_plan_id', $mealPlanIds)->findOrFail($id);
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil dihapus dari rencana makan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus menu: ' . $e->getMessage()
            ], 500);
        }
    }
}
