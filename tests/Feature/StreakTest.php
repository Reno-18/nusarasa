<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MealPlan;
use App\Models\MealPlanItem;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class StreakTest extends TestCase
{
    use RefreshDatabase;

    public function test_streak_increases_when_planning_next_week(): void
    {
        $user = User::factory()->create();
        $chef = User::factory()->create(['role' => 'chef']);
        $recipe = Recipe::factory()->create(['user_id' => $chef->id]);

        $this->assertDatabaseHas('meal_plan_streaks', [
            'user_id' => $user->id,
            'current_streak' => 0
        ]);

        $weekStart = now()->startOfWeek();
        $weekStartStr = $weekStart->toDateString();
        $weekIdentifier = $weekStart->format('Y-\WW');

        // Add a meal plan
        $mealPlan = MealPlan::create([
            'user_id' => $user->id,
            'week_start' => $weekStartStr
        ]);

        // Add items for 5 distinct days to complete the weekly plan
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        foreach ($days as $day) {
            MealPlanItem::create([
                'meal_plan_id' => $mealPlan->id,
                'recipe_id' => $recipe->id,
                'day_of_week' => $day,
                'meal_type' => 'breakfast'
            ]);
        }

        // Run command for the target week
        $result = Artisan::call('meal-plan:update-streaks', [
            '--week' => $weekStartStr
        ]);

        $streak = $user->streak()->first();
        $this->assertNotNull($streak);
        $this->assertEquals($weekIdentifier, $streak->last_completed_week);
    }

    public function test_streak_updater_command_resets_streak_if_not_planned(): void
    {
        $user = User::factory()->create();
        $streak = $user->streak;
        
        // Mock a streak of 3, but the last completed week was 2 weeks ago
        $streak->update([
            'current_streak' => 3,
            'last_completed_week' => now()->subWeeks(2)->startOfWeek()->format('Y-\WW')
        ]);

        // Run the command
        Artisan::call('meal-plan:update-streaks');

        $streak->refresh();
        $this->assertEquals(0, $streak->current_streak);
    }
}
