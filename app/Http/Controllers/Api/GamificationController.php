<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Services\GamificationService;
use App\Traits\ApiResponse;

class GamificationController extends Controller
{
    use ApiResponse;

    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Get points, level, and progress information for the authenticated user.
     */
    public function getPoints()
    {
        $user = auth()->user();
        $userPoints = $user->points()->firstOrCreate(
            ['user_id' => $user->id],
            ['points' => 0, 'level' => 'Koki Pemula']
        );

        $points = $userPoints->points;
        $level = $userPoints->level;
        $nextThreshold = $this->gamificationService->getNextLevelThreshold($points);
        
        $levels = [
            ['name' => 'Koki Pemula', 'threshold' => 0],
            ['name' => 'Asisten Koki', 'threshold' => 100],
            ['name' => 'Juru Masak', 'threshold' => 300],
            ['name' => 'Chef de Partie', 'threshold' => 600],
            ['name' => 'Sous Chef', 'threshold' => 1000],
            ['name' => 'Head Chef', 'threshold' => 2000],
        ];
        
        $currentThreshold = 0;
        foreach ($levels as $l) {
            if ($l['name'] === $level) {
                $currentThreshold = $l['threshold'];
                break;
            }
        }

        $range = $nextThreshold - $currentThreshold;
        $progress = $range > 0 ? (($points - $currentThreshold) / $range) * 100 : 100;
        $progress = min(100, max(0, round($progress, 1)));

        return $this->successResponse([
            'points' => $points,
            'level' => $level,
            'next_level_threshold' => $nextThreshold,
            'current_level_threshold' => $currentThreshold,
            'progress_percentage' => $progress,
        ], 'Poin dan level pengguna berhasil diambil');
    }

    /**
     * Get unlocked and locked badges for the authenticated user.
     */
    public function getBadges()
    {
        $user = auth()->user();
        $unlocked = $user->badges()->get();
        $unlockedIds = $unlocked->pluck('id')->toArray();
        $locked = Badge::whereNotIn('id', $unlockedIds)->get();

        return $this->successResponse([
            'unlocked' => $unlocked,
            'locked' => $locked,
        ], 'Lencana pengguna berhasil diambil');
    }
}
