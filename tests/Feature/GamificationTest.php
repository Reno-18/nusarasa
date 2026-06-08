<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_points_are_created_automatically(): void
    {
        $user = User::factory()->create();
        
        $this->assertDatabaseHas('user_points', [
            'user_id' => $user->id,
            'points' => 0,
            'level' => 'Koki Pemula',
        ]);
    }

    public function test_user_earns_points_when_saving_recipe(): void
    {
        $user = User::factory()->create();
        $chef = User::factory()->create(['role' => 'chef']);
        $recipe = Recipe::factory()->create(['user_id' => $chef->id]);

        // Save a recipe using POST to /api/saved-recipes
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/saved-recipes', [
            'recipe_id' => $recipe->id
        ]);
        
        $response->assertCreated();
        $user->refresh();
        $this->assertEquals(3, $user->points->points); // 3 points for saving recipe based on pointsMap
    }

    public function test_user_earns_points_when_rating_recipe(): void
    {
        $user = User::factory()->create();
        $chef = User::factory()->create(['role' => 'chef']);
        $recipe = Recipe::factory()->create(['user_id' => $chef->id]);

        // Rate a recipe using POST to /api/recipes/{id}/ratings
        $response = $this->actingAs($user, 'sanctum')->postJson("/api/recipes/{$recipe->id}/ratings", [
            'score' => 5,
            'comment' => 'Enak sekali!'
        ]);
        
        $response->assertCreated();
        $user->refresh();
        $this->assertEquals(15, $user->points->points); // 10 pts for rating + 5 pts for comment
    }

    public function test_toggle_leaderboard_visibility(): void
    {
        $user = User::factory()->create(['show_on_leaderboard' => true]);

        $response = $this->actingAs($user)->patch(route('profile.leaderboard-toggle'));

        $response->assertRedirect();
        $user->refresh();
        $this->assertFalse($user->show_on_leaderboard);
    }

    public function test_api_gamification_stats(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/user/points');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    'points',
                    'level',
                    'next_level_threshold',
                    'current_level_threshold',
                    'progress_percentage'
                ]
            ]);
    }
}
