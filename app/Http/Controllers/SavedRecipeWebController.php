<?php

namespace App\Http\Controllers;

use App\Models\SavedRecipe;
use Illuminate\Http\Request;

class SavedRecipeWebController extends Controller
{
    public function index()
    {
        $savedRecipes = SavedRecipe::where('user_id', auth()->id())
            ->with('recipe')
            ->latest()
            ->get();

        return view('saved.index', compact('savedRecipes'));
    }
}
