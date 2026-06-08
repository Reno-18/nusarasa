<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Collection;

class BadgeService
{
    /**
     * Evaluate and potentially unlock new badges for a user under a certain event trigger.
     */
    public function checkAndAward(User $user, string $type, $currentValue = null): array
    {
        if ($currentValue === null) {
            $currentValue = $this->getCurrentValueForType($user, $type);
        }

        // Get badges of this type that the user has not unlocked yet
        $unlockedBadgeIds = $user->badges()->pluck('badges.id')->toArray();
        $candidateBadges = Badge::where('condition_type', $type)
            ->whereNotIn('id', $unlockedBadgeIds)
            ->get();

        $newlyUnlocked = [];
        foreach ($candidateBadges as $badge) {
            if ($currentValue >= $badge->condition_value) {
                $user->badges()->attach($badge->id, ['unlocked_at' => now()]);
                $newlyUnlocked[] = $badge;
            }
        }

        return $newlyUnlocked;
    }

    /**
     * Get the list of all unlocked badges for a user.
     */
    public function getUnlockedBadges(User $user): Collection
    {
        return $user->badges()->get();
    }

    /**
     * Get dynamic progress value for a condition type.
     */
    public function getCurrentValueForType(User $user, string $type)
    {
        switch ($type) {
            case 'saved_recipes':
                return $user->savedRecipes()->count();
            case 'rated_recipes':
                return $user->ratings()->count();
            case 'published_recipes':
                return $user->recipes()->where('is_approved', true)->count();
            case 'meal_plan_streak':
                return $user->streak ? $user->streak->current_streak : 0;
            case 'points':
                return $user->points ? $user->points->points : 0;
            default:
                return 0;
        }
    }
}
