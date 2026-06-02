<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class ChefController extends Controller
{
    public function dashboard()
    {
        $recipes = Recipe::where('user_id', auth()->id())
            ->withCount('ratings')
            ->latest()
            ->get();

        $totalRecipes = $recipes->count();
        $approvedRecipes = $recipes->where('is_approved', true)->count();
        $pendingRecipes = $recipes->where('is_approved', false)->count();
        $totalViews = $recipes->sum('view_count');

        return view('chef.dashboard', compact('recipes', 'totalRecipes', 'approvedRecipes', 'pendingRecipes', 'totalViews'));
    }

    public function index()
    {
        // Get all chefs and admins (Head Chefs)
        $chefs = \App\Models\User::whereIn('role', ['chef', 'admin'])
            ->withCount(['recipes as total_recipes' => function ($query) {
                $query->where('is_approved', true);
            }])
            ->withSum(['recipes as total_ratings' => function ($query) {
                $query->where('is_approved', true);
            }], 'view_count') // Alternatively, we can use a custom logic for stars
            ->get();

        // Calculate average stars based on the ratings relation of their recipes
        foreach ($chefs as $chef) {
            $chefRecipes = Recipe::where('user_id', $chef->id)->where('is_approved', true)->with('ratings')->get();
            $totalScore = 0;
            $totalVotes = 0;

            foreach ($chefRecipes as $recipe) {
                $totalScore += $recipe->ratings->sum('score');
                $totalVotes += $recipe->ratings->count();
            }

            $chef->average_rating = $totalVotes > 0 ? round($totalScore / $totalVotes, 1) : 0;
            $chef->total_votes = $totalVotes;
        }

        // Sort by admin first (Head Chef), then by average rating, then by total recipes
        $chefs = $chefs->sortByDesc(function ($chef) {
            $roleScore = $chef->role === 'admin' ? 1000 : 0;
            return $roleScore + $chef->average_rating + ($chef->total_recipes * 0.1);
        })->values();

        return view('chefs.index', compact('chefs'));
    }
}
