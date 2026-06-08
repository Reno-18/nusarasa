<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Clear cache before each test
        Cache::flush();
    }

    public function test_leaderboard_page_renders(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('leaderboard.chefs'));

        $response->assertOk()
            ->assertSee('Chef')
            ->assertSee('Terbaik');
    }

    public function test_only_visible_users_on_leaderboard(): void
    {
        $visibleUser = User::factory()->create(['name' => 'Visible Chef', 'show_on_leaderboard' => true]);
        $hiddenUser = User::factory()->create(['name' => 'Hidden Chef', 'show_on_leaderboard' => false]);

        $response = $this->get(route('leaderboard.chefs'));

        $response->assertOk()
            ->assertSee('Visible Chef')
            ->assertDontSee('Hidden Chef');
    }

    public function test_api_leaderboard_endpoint(): void
    {
        $user = User::factory()->create(['show_on_leaderboard' => true]);

        $response = $this->getJson('/api/leaderboard/chefs?period=all');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'score_display'
                    ]
                ]
            ]);
    }
}
