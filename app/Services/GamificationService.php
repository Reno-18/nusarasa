<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPoints;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * Define level names and their lower-bound point thresholds.
     */
    protected array $levels = [
        ['name' => 'Koki Pemula', 'threshold' => 0],
        ['name' => 'Asisten Koki', 'threshold' => 100],
        ['name' => 'Juru Masak', 'threshold' => 300],
        ['name' => 'Chef de Partie', 'threshold' => 600],
        ['name' => 'Sous Chef', 'threshold' => 1000],
        ['name' => 'Head Chef', 'threshold' => 2000],
    ];

    /**
     * Award points to a user for a specific action.
     */
    public function awardPoints(User $user, string $action): array
    {
        $pointsMap = [
            'rate_recipe' => 10,
            'comment_recipe' => 5,
            'save_recipe' => 3,
            'publish_recipe' => 30,
            'complete_meal_plan' => 50,
        ];

        $amount = $pointsMap[$action] ?? 0;
        if ($amount === 0) {
            return [
                'points_awarded' => 0,
                'new_points' => $user->points ? $user->points->points : 0,
                'level' => $user->points ? $user->points->level : 'Koki Pemula',
                'level_up' => false,
                'new_badges' => [],
            ];
        }

        $userPoints = $user->points()->firstOrCreate(
            ['user_id' => $user->id],
            ['points' => 0, 'level' => 'Koki Pemula']
        );

        $oldPoints = $userPoints->points;
        $userPoints->points += $amount;
        $newLevel = $this->getLevel($userPoints->points);
        $userPoints->level = $newLevel;
        $userPoints->save();

        // Check if points badge is unlocked
        $badgeService = app(BadgeService::class);
        $newBadges = $badgeService->checkAndAward($user, 'points', $userPoints->points);

        return [
            'points_awarded' => $amount,
            'new_points' => $userPoints->points,
            'level' => $newLevel,
            'level_up' => $this->getLevel($oldPoints) !== $newLevel,
            'new_badges' => $newBadges,
        ];
    }

    /**
     * Determine level name based on points.
     */
    public function getLevel(int $points): string
    {
        $currentLevel = 'Koki Pemula';
        foreach ($this->levels as $level) {
            if ($points >= $level['threshold']) {
                $currentLevel = $level['name'];
            }
        }
        return $currentLevel;
    }

    /**
     * Get the point threshold for the next level.
     */
    public function getNextLevelThreshold(int $points): int
    {
        foreach ($this->levels as $level) {
            if ($level['threshold'] > $points) {
                return $level['threshold'];
            }
        }
        return 2000; // Cap at max level threshold
    }

    /**
     * Checks if a user filled 5+ days in their meal plan this week.
     */
    public function checkWeeklyPlanCompletion(User $user, $weekStart = null): bool
    {
        if ($weekStart === null) {
            $weekStart = now()->startOfWeek()->format('Y-m-d');
        }

        $plan = $user->mealPlans()->where('week_start', $weekStart)->first();
        if (!$plan) {
            return false;
        }

        // Count distinct days filled
        $daysCount = $plan->items()->distinct()->count('day_of_week');

        return $daysCount >= 5;
    }
}
