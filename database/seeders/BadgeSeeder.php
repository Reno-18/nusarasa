<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Favorit Pemula',
                'slug' => 'favorit-pemula',
                'description' => 'Menyimpan 1 resep ke daftar favorit.',
                'icon' => '❤️',
                'condition_type' => 'saved_recipes',
                'condition_value' => 1,
            ],
            [
                'name' => 'Kolektor Resep',
                'slug' => 'kolektor-resep',
                'description' => 'Menyimpan 5 resep ke daftar favorit.',
                'icon' => '📚',
                'condition_type' => 'saved_recipes',
                'condition_value' => 5,
            ],
            [
                'name' => 'Kritikus Makanan',
                'slug' => 'kritikus-makanan',
                'description' => 'Memberikan 1 ulasan/rating pada resep.',
                'icon' => '⭐',
                'condition_type' => 'rated_recipes',
                'condition_value' => 1,
            ],
            [
                'name' => 'Pencicip Handal',
                'slug' => 'pencicip-handal',
                'description' => 'Memberikan 5 ulasan/rating pada resep.',
                'icon' => '👅',
                'condition_type' => 'rated_recipes',
                'condition_value' => 5,
            ],
            [
                'name' => 'Chef Berbakat',
                'slug' => 'chef-berbakat',
                'description' => 'Mempublikasikan 1 resep yang disetujui.',
                'icon' => '🍳',
                'condition_type' => 'published_recipes',
                'condition_value' => 1,
            ],
            [
                'name' => 'Master Dapur',
                'slug' => 'master-dapur',
                'description' => 'Mempublikasikan 5 resep yang disetujui.',
                'icon' => '👑',
                'condition_type' => 'published_recipes',
                'condition_value' => 5,
            ],
            [
                'name' => 'Perencana Rajin',
                'slug' => 'perencana-rajin',
                'description' => 'Mencapai streak rencana makan mingguan selama 1 minggu.',
                'icon' => '📅',
                'condition_type' => 'meal_plan_streak',
                'condition_value' => 1,
            ],
            [
                'name' => 'Konsistensi Tinggi',
                'slug' => 'konsistensi-tinggi',
                'description' => 'Mencapai streak rencana makan mingguan selama 4 minggu berturut-turut.',
                'icon' => '🔥',
                'condition_type' => 'meal_plan_streak',
                'condition_value' => 4,
            ],
            [
                'name' => 'Pecinta NusaRasa',
                'slug' => 'pecinta-nusarasa',
                'description' => 'Mendapatkan total 100 poin.',
                'icon' => '🌟',
                'condition_type' => 'points',
                'condition_value' => 100,
            ],
            [
                'name' => 'Bintang Dapur',
                'slug' => 'bintang-dapur',
                'description' => 'Mendapatkan total 500 poin.',
                'icon' => '💎',
                'condition_type' => 'points',
                'condition_value' => 500,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
