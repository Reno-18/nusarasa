<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LeaderboardController extends Controller
{
    use ApiResponse;

    /**
     * Display rankings based on total points or monthly points.
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');

        // Cache the leaderboard for 60 minutes as per spec
        $cacheKey = 'leaderboard_api_' . $period;
        $users = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($period) {
            $query = User::where('show_on_leaderboard', true)
                ->with('points')
                ->whereHas('points');

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

                return $mapped->sortByDesc('score_display')->values();
            } else {
                $mapped = $allUsers->map(function ($user) {
                    $user->score_display = $user->points ? $user->points->points : 0;
                    return $user;
                });
                return $mapped->sortByDesc('score_display')->values();
            }
        });

        return $this->successResponse($users, 'Peringkat leaderboard berhasil diambil');
    }
}
