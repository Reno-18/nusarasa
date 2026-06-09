<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LeaderboardController extends Controller
{
    /**
     * Display the public leaderboard page.
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');
        $role = $request->get('role', 'chef'); // Default to chef

        $query = User::where('show_on_leaderboard', true)
            ->where('role', $role)
            ->with('points');

        $allUsers = $query->get();

        if ($period === 'month') {
            $sub30Days = now()->subDays(30);
            
            $mapped = $allUsers->map(function ($user) use ($sub30Days) {
                $ratingsCount = $user->ratings()->where('created_at', '>=', $sub30Days)->count();
                
                $commentsCount = $user->ratings()
                    ->where('created_at', '>=', $sub30Days)
                    ->whereNotNull('comment')
                    ->where('comment', '!=', '')
                    ->count();
                    
                $savedCount = $user->savedRecipes()
                    ->where('created_at', '>=', $sub30Days)
                    ->count();
                    
                $publishedCount = $user->recipes()
                    ->where('is_approved', true)
                    ->where('updated_at', '>=', $sub30Days)
                    ->count();

                $monthlyScore = ($ratingsCount * 10) + ($commentsCount * 5) + ($savedCount * 3) + ($publishedCount * 30);
                $user->score_display = $monthlyScore;
                return $user;
            });

            $users = $mapped->sortByDesc('score_display')->values();
        } else {
            $mapped = $allUsers->map(function ($user) {
                $user->score_display = $user->points ? $user->points->points : 0;
                return $user;
            });
            $users = $mapped->sortByDesc('score_display')->values();
        }

        return view('leaderboard.chefs', compact('users', 'period', 'role'));
    }
}
