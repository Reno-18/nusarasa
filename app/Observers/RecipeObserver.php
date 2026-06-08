<?php

namespace App\Observers;

use App\Models\Recipe;
use App\Services\GamificationService;
use App\Services\BadgeService;

class RecipeObserver
{
    public function created(Recipe $recipe): void
    {
        if ($recipe->is_approved) {
            $this->awardPublishPoints($recipe);
        }
    }

    public function updated(Recipe $recipe): void
    {
        if ($recipe->isDirty('is_approved') && $recipe->is_approved && !$recipe->getOriginal('is_approved')) {
            $this->awardPublishPoints($recipe);
        }
    }

    protected function awardPublishPoints(Recipe $recipe): void
    {
        $user = $recipe->user;
        if (!$user) return;

        $gamificationService = app(GamificationService::class);
        $badgeService = app(BadgeService::class);

        // Award points for publishing
        $res = $gamificationService->awardPoints($user, 'publish_recipe');
        $totalAwarded = $res['points_awarded'];
        $unlockedBadges = $res['new_badges'] ?? [];

        // Check for published_recipes badges
        $additionalBadges = $badgeService->checkAndAward($user, 'published_recipes');
        $unlockedBadges = array_merge($unlockedBadges, $additionalBadges);

        if (session()) {
            session()->flash('gamification_points', $totalAwarded);
            if (!empty($unlockedBadges)) {
                session()->flash('gamification_badges', collect($unlockedBadges)->pluck('name')->toArray());
            }
        }
    }
}
