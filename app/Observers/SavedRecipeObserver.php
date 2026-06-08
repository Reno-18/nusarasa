<?php

namespace App\Observers;

use App\Models\SavedRecipe;
use App\Services\GamificationService;
use App\Services\BadgeService;

class SavedRecipeObserver
{
    public function created(SavedRecipe $savedRecipe): void
    {
        $user = $savedRecipe->user;
        if (!$user) return;

        $gamificationService = app(GamificationService::class);
        $badgeService = app(BadgeService::class);

        // Award points for saving
        $res = $gamificationService->awardPoints($user, 'save_recipe');
        $totalAwarded = $res['points_awarded'];
        $unlockedBadges = $res['new_badges'] ?? [];

        // Check for saved_recipes badges
        $additionalBadges = $badgeService->checkAndAward($user, 'saved_recipes');
        $unlockedBadges = array_merge($unlockedBadges, $additionalBadges);

        if (session()) {
            session()->flash('gamification_points', $totalAwarded);
            if (!empty($unlockedBadges)) {
                session()->flash('gamification_badges', collect($unlockedBadges)->pluck('name')->toArray());
            }
        }
    }
}
