<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Recipe;
use App\Models\MealPlan;
use App\Models\MealPlanItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListTest extends TestCase
{
    use RefreshDatabase;

    public function test_shopping_list_page_redirects_if_no_meal_plan_exists(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('meal-plan.shopping-list'));

        $response->assertRedirect(route('meal-plan.index'));
        $response->assertSessionHas('error', 'Buat rencana makan terlebih dahulu!');
    }

    public function test_shopping_list_page_renders_with_recipes_if_meal_plan_exists(): void
    {
        $user = User::factory()->create();
        $chef = User::factory()->create(['role' => 'chef']);
        
        $recipe1 = Recipe::factory()->create([
            'user_id' => $chef->id,
            'title' => 'Sayur Asem',
            'ingredients' => "Jagung manis\nKacang panjang\nLabu siam",
            'is_approved' => true,
        ]);

        $recipe2 = Recipe::factory()->create([
            'user_id' => $chef->id,
            'title' => 'Ayam Goreng',
            'ingredients' => "Ayam 1 ekor\nBawang putih\nKetumbar",
            'is_approved' => true,
        ]);

        // Create meal plan
        $mealPlan = MealPlan::create([
            'user_id' => $user->id,
            'week_start' => now()->startOfWeek()->toDateString(),
        ]);

        // Add items
        MealPlanItem::create([
            'meal_plan_id' => $mealPlan->id,
            'recipe_id' => $recipe1->id,
            'day_of_week' => 'monday',
            'meal_type' => 'breakfast',
        ]);

        MealPlanItem::create([
            'meal_plan_id' => $mealPlan->id,
            'recipe_id' => $recipe2->id,
            'day_of_week' => 'monday',
            'meal_type' => 'lunch',
        ]);

        // Add an API item
        MealPlanItem::create([
            'meal_plan_id' => $mealPlan->id,
            'meal_api_id' => '52772',
            'meal_api_title' => 'Teriyaki Chicken Casserole',
            'day_of_week' => 'tuesday',
            'meal_type' => 'dinner',
        ]);

        $response = $this->actingAs($user)->get(route('meal-plan.shopping-list'));

        $response->assertOk();
        $response->assertSee('Sayur Asem');
        $response->assertSee('Ayam Goreng');
        $response->assertSee('Teriyaki Chicken Casserole');
        $response->assertSee('Senin');
        $response->assertSee('Sarapan');
        $response->assertSee('Makan Siang');
    }

    public function test_generating_shopping_list_validation_fails_if_no_days_selected(): void
    {
        $user = User::factory()->create();
        $mealPlan = MealPlan::create([
            'user_id' => $user->id,
            'week_start' => now()->startOfWeek()->toDateString(),
        ]);

        $response = $this->actingAs($user)
            ->from(route('meal-plan.shopping-list'))
            ->post(route('meal-plan.shopping-list.generate'), [
                'days' => [],
            ]);

        $response->assertRedirect(route('meal-plan.shopping-list'));
        $response->assertSessionHas('error', 'Pilih minimal satu hari untuk daftar belanja.');
    }

    public function test_generating_shopping_list_by_day_returns_correct_ingredients(): void
    {
        $user = User::factory()->create();
        $chef = User::factory()->create(['role' => 'chef']);

        $recipe1 = Recipe::factory()->create([
            'user_id'      => $chef->id,
            'title'        => 'Resep A',
            'ingredients'  => "Bahan A1\nBahan A2",
            'is_approved'  => true,
        ]);

        $recipe2 = Recipe::factory()->create([
            'user_id'      => $chef->id,
            'title'        => 'Resep B',
            'ingredients'  => "Bahan B1\nBahan B2",
            'is_approved'  => true,
        ]);

        $mealPlan = MealPlan::create([
            'user_id'    => $user->id,
            'week_start' => now()->startOfWeek()->toDateString(),
        ]);

        // Recipe A on Monday, Recipe B on Tuesday
        MealPlanItem::create([
            'meal_plan_id' => $mealPlan->id,
            'recipe_id'    => $recipe1->id,
            'day_of_week'  => 'monday',
            'meal_type'    => 'breakfast',
        ]);

        MealPlanItem::create([
            'meal_plan_id' => $mealPlan->id,
            'recipe_id'    => $recipe2->id,
            'day_of_week'  => 'tuesday',
            'meal_type'    => 'lunch',
        ]);

        // Select only Monday — should see Resep A ingredients but NOT Resep B
        $response = $this->actingAs($user)->post(route('meal-plan.shopping-list.generate'), [
            'days' => ['monday'],
        ]);

        $response->assertOk();
        $response->assertSee('Resep A');
        $response->assertSee('Bahan A1');
        $response->assertSee('Bahan A2');
        $response->assertDontSee('Bahan B1');
        $response->assertDontSee('Bahan B2');
    }

    public function test_delete_meal_plan_item_via_web_route(): void
    {
        $user = User::factory()->create();
        $chef = User::factory()->create(['role' => 'chef']);
        $recipe = Recipe::factory()->create(['user_id' => $chef->id]);

        $mealPlan = MealPlan::create([
            'user_id' => $user->id,
            'week_start' => now()->startOfWeek()->toDateString(),
        ]);

        $item = MealPlanItem::create([
            'meal_plan_id' => $mealPlan->id,
            'recipe_id' => $recipe->id,
            'day_of_week' => 'monday',
            'meal_type' => 'breakfast',
        ]);

        // Attempt deleting via web route
        $response = $this->actingAs($user)->delete(route('meal-plan.items.destroy', $item->id));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Menu berhasil dihapus dari rencana makan'
        ]);

        $this->assertDatabaseMissing('meal_plan_items', [
            'id' => $item->id,
        ]);
    }
}
