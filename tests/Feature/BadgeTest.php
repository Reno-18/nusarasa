<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\BadgeSeeder;
use Tests\TestCase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed default badges
        $this->seed(BadgeSeeder::class);
    }

    public function test_badge_unlocked_on_first_saved_recipe(): void
    {
        $user = User::factory()->create();
        $chef = User::factory()->create(['role' => 'chef']);
        $recipe = Recipe::factory()->create(['user_id' => $chef->id]);

        $this->assertCount(0, $user->badges);

        // Save a recipe
        $this->actingAs($user, 'sanctum')->postJson('/api/saved-recipes', [
            'recipe_id' => $recipe->id
        ]);

        $user->refresh();
        $this->assertCount(1, $user->badges);
        $this->assertEquals('favorit-pemula', $user->badges->first()->slug);
    }

    public function test_badge_unlocked_on_first_rated_recipe(): void
    {
        $user = User::factory()->create();
        $chef = User::factory()->create(['role' => 'chef']);
        $recipe = Recipe::factory()->create(['user_id' => $chef->id]);

        $this->assertCount(0, $user->badges);

        // Rate a recipe
        $this->actingAs($user, 'sanctum')->postJson("/api/recipes/{$recipe->id}/ratings", [
            'score' => 4,
            'comment' => 'Cukup bagus.'
        ]);

        $user->refresh();
        $this->assertCount(1, $user->badges);
        $this->assertEquals('kritikus-makanan', $user->badges->first()->slug);
    }

    public function test_badge_unlocked_on_published_recipe(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);

        $this->assertCount(0, $chef->badges);

        // Create recipe
        $recipe = Recipe::factory()->create([
            'user_id' => $chef->id,
            'is_approved' => false
        ]);

        // When chef publishes and admin approves, the observer handles the points and badge checks
        $recipe->is_approved = true;
        $recipe->save();

        $chef->refresh();
        $this->assertCount(1, $chef->badges);
        $this->assertEquals('chef-berbakat', $chef->badges->first()->slug);
    }

    public function test_api_badges_endpoint(): void
    {
        $user = User::factory()->create();
        $badge = Badge::first();
        $user->badges()->attach($badge->id, ['unlocked_at' => now()]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/user/badges');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data.unlocked');
    }
}
