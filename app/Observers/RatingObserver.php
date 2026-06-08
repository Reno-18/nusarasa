<?php

namespace App\Observers;

use App\Models\Rating;
use App\Services\GamificationService;
use App\Services\BadgeService;

class RatingObserver
{
    public function created(Rating $rating): void
    {
        $user = $rating->user;
        if (!$user) return;

        $gamificationService = app(GamificationService::class);
        $badgeService = app(BadgeService::class);

        // Award points for rating
        $res = $gamificationService->awardPoints($user, 'rate_recipe');
        $totalAwarded = $res['points_awarded'];
        $unlockedBadges = $res['new_badges'] ?? [];

        // Award points for comment if present
        if (!empty($rating->comment)) {
            $commentRes = $gamificationService->awardPoints($user, 'comment_recipe');
            $totalAwarded += $commentRes['points_awarded'];
            $unlockedBadges = array_merge($unlockedBadges, $commentRes['new_badges'] ?? []);
        }

        // Check for rated_recipes badges
        $additionalBadges = $badgeService->checkAndAward($user, 'rated_recipes');
        $unlockedBadges = array_merge($unlockedBadges, $additionalBadges);

        // Flash message to session
        if (session()) {
            session()->flash('gamification_points', $totalAwarded);
            if (!empty($unlockedBadges)) {
                session()->flash('gamification_badges', collect($unlockedBadges)->pluck('name')->toArray());
            }
        }
    }
}
