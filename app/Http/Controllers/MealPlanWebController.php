<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Illuminate\Http\Request;

class MealPlanWebController extends Controller
{
    public function index()
    {
        $mealPlan = MealPlan::where('user_id', auth()->id())
            ->with('items.recipe')
            ->latest()
            ->first();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $mealTypes = ['breakfast', 'lunch', 'dinner'];

        return view('meal-plan.index', compact('mealPlan', 'days', 'mealTypes'));
    }
}
