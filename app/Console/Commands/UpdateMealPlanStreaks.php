<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\MealPlanStreak;
use App\Services\GamificationService;
use App\Services\BadgeService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateMealPlanStreaks extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'meal-plan:update-streaks {--week= : The week start date (YYYY-MM-DD) to check. Defaults to last week.}';

    /**
     * The console command description.
     */
    protected $description = 'Checks all users meal plans for the prior week, updates streaks, and awards points/badges.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gamificationService = app(GamificationService::class);
        $badgeService = app(BadgeService::class);

        // Determine the target week (last week's Monday)
        $targetWeekInput = $this->option('week');
        if ($targetWeekInput) {
            $targetWeek = Carbon::parse($targetWeekInput);
        } else {
            $targetWeek = now()->subWeek()->startOfWeek();
        }

        $weekStartStr = $targetWeek->format('Y-m-d');
        $weekIdentifier = $targetWeek->format('Y-\WW');

        $this->info("Updating streaks for week starting: {$weekStartStr} ({$weekIdentifier})");

        $users = User::all();

        foreach ($users as $user) {
            $completed = $gamificationService->checkWeeklyPlanCompletion($user, $weekStartStr);
            
            $streak = MealPlanStreak::firstOrCreate(
                ['user_id' => $user->id],
                ['current_streak' => 0, 'longest_streak' => 0]
            );

            if ($completed) {
                if ($streak->last_completed_week === $weekIdentifier) {
                    $this->line("User {$user->name} already processed for week {$weekIdentifier}. Skipping.");
                    continue;
                }

                $streak->current_streak++;
                if ($streak->current_streak > $streak->longest_streak) {
                    $streak->longest_streak = $streak->current_streak;
                }
                $streak->last_completed_week = $weekIdentifier;
                $streak->save();

                // Award points (complete_meal_plan = 50 points)
                $gamificationService->awardPoints($user, 'complete_meal_plan');

                // Streak milestone bonus: +100 points every 5 weeks
                if ($streak->current_streak > 0 && $streak->current_streak % 5 === 0) {
                    $userPoints = $user->points()->firstOrCreate(
                        ['user_id' => $user->id],
                        ['points' => 0, 'level' => 'Koki Pemula']
                    );
                    $userPoints->points += 100;
                    $userPoints->save();
                    
                    $this->info("Awarded 100 bonus points to {$user->name} for reaching streak {$streak->current_streak}");
                }

                // Check and award streak-based badges
                $badgeService->checkAndAward($user, 'meal_plan_streak', $streak->current_streak);

                $this->info("User {$user->name} completed week. Current Streak: {$streak->current_streak}");
            } else {
                if ($streak->last_completed_week !== $weekIdentifier) {
                    $streak->current_streak = 0;
                    $streak->save();
                    $this->line("User {$user->name} did not complete week. Streak reset.");
                }
            }
        }

        $this->info("Streak update complete.");
        return 0;
    }
}
