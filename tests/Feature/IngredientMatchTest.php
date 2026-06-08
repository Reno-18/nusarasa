<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientMatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_by_ingredients_page_renders(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/recipes/by-ingredients');

        $response->assertOk()
            ->assertSee('Masak dari Bahan');
    }

    public function test_match_recipes_by_ingredients(): void
    {
        $chef = User::factory()->create(['role' => 'chef']);

        // Create a recipe with specific ingredients
        $recipe1 = Recipe::factory()->create([
            'user_id' => $chef->id,
            'title' => 'Sayur Sop Enak',
            'ingredients' => "Wortel 2 buah\nKentang 1 buah\nKol secukupnya\nBawang putih 3 siung\nDaun seledri\nGaram",
            'is_approved' => true
        ]);

        $recipe2 = Recipe::factory()->create([
            'user_id' => $chef->id,
            'title' => 'Nasi Goreng Spesial',
            'ingredients' => "Nasi dingin 1 piring\nKecap manis 2 sdm\nTelur ayam 1 butir\nBawang putih 2 siung",
            'is_approved' => true
        ]);

        // Query with matching ingredients
        $response = $this->get('/recipes/by-ingredients?ingredients=Wortel,Kentang,Kol,Bawang+putih,Daun+seledri,Garam');

        $response->assertOk();
        // Since sayur sop has 6 matches (>= 5 needed), it should be listed. Nasi goreng only matches Bawang putih (1 match), so it should not be listed.
        $response->assertSee('Sayur Sop Enak');
        $response->assertDontSee('Nasi Goreng Spesial');
    }
}
