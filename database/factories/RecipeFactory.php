<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'category' => 'Main Course',
            'origin' => 'Indonesia',
            'ingredients' => "Bahan 1\nBahan 2\nBahan 3",
            'instructions' => "Langkah 1\nLangkah 2\nLangkah 3",
            'image_url' => 'https://placehold.co/400x300?text=Recipe',
            'source' => 'local',
            'is_approved' => true,
        ];
    }
}
