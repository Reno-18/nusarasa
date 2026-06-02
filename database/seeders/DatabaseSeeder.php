<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Users
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@nusarasa.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $chef1 = User::create([
            'name' => 'Chef Budi',
            'email' => 'chef1@nusarasa.com',
            'password' => Hash::make('password'),
            'role' => 'chef',
        ]);

        $chef2 = User::create([
            'name' => 'Chef Sari',
            'email' => 'chef2@nusarasa.com',
            'password' => Hash::make('password'),
            'role' => 'chef',
        ]);

        User::create([
            'name' => 'Andi',
            'email' => 'user1@nusarasa.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Bela',
            'email' => 'user2@nusarasa.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Citra',
            'email' => 'user3@nusarasa.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create Recipes
        $recipes = [
            [
                'user_id' => $chef1->id,
                'title' => 'Nasi Goreng',
                'category' => 'Main Course',
                'origin' => 'Indonesia',
                'ingredients' => "Nasi putih 2 piring\nTelur 2 butir\nBawang merah 5 siung\nBawang putih 3 siung\nCabai merah 3 buah\nKecap manis 2 sdm\nGaram secukupnya\nMinyak goreng",
                'instructions' => "1. Panaskan minyak, tumis bawang merah dan bawang putih hingga harum\n2. Masukkan cabai, aduk rata\n3. Masukkan telur, orak-arik hingga matang\n4. Masukkan nasi putih, aduk rata\n5. Tambahkan kecap manis dan garam, aduk hingga merata\n6. Sajikan hangat",
                'image_url' => 'https://images.unsplash.com/photo-1603133872878-6966b46b79b5?auto=format&fit=crop&w=600&q=80',
                'source' => 'local',
                'is_approved' => true,
            ],
            [
                'user_id' => $chef2->id,
                'title' => 'Rendang',
                'category' => 'Main Course',
                'origin' => 'Padang',
                'ingredients' => "Daging sapi 1 kg\nSantan kental 1 liter\nSerai 2 batang\nDaun jeruk 5 lembar\nDaun kunyit 2 lembar\nCabai merah 10 buah\nBawang merah 10 siung\nBawang putih 6 siung\nJahe 3 cm\nKunyit 2 cm\nGaram secukupnya",
                'instructions' => "1. Haluskan cabai, bawang merah, bawang putih, jahe, dan kunyit\n2. Tumis bumbu halus hingga harum\n3. Masukkan daging, aduk hingga berubah warna\n4. Tuang santan, masukkan serai, daun jeruk, dan daun kunyit\n5. Masak dengan api kecil hingga santan menyusut dan daging empuk (sekitar 3-4 jam)\n6. Aduk sesekali agar tidak gosong\n7. Sajikan dengan nasi hangat",
                'image_url' => 'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f?auto=format&fit=crop&w=600&q=80',
                'source' => 'local',
                'is_approved' => true,
            ],
            [
                'user_id' => $chef1->id,
                'title' => 'Soto Ayam',
                'category' => 'Soup',
                'origin' => 'Jawa Tengah',
                'ingredients' => "Ayam 1 ekor\nBawang merah 8 siung\nBawang putih 4 siung\nKunyit 2 cm\nJahe 3 cm\nSerai 2 batang\nDaun salam 3 lembar\nDaun jeruk 4 lembar\nGaram secukupnya\nMerica secukupnya\nMinyak goreng",
                'instructions' => "1. Rebus ayam hingga matang, angkat dan suwir-suwir\n2. Haluskan bawang merah, bawang putih, kunyit, dan jahe\n3. Tumis bumbu halus hingga harum\n4. Masukkan bumbu tumis ke dalam kaldu ayam\n5. Tambahkan serai, daun salam, dan daun jeruk\n6. Masak hingga mendidih, beri garam dan merica\n7. Sajikan dengan nasi, tauge, dan kerupuk",
                'image_url' => 'https://images.unsplash.com/photo-1547496502-affa22d38842?auto=format&fit=crop&w=600&q=80',
                'source' => 'local',
                'is_approved' => true,
            ],
            [
                'user_id' => $chef2->id,
                'title' => 'Gado-gado',
                'category' => 'Salad',
                'origin' => 'Jakarta',
                'ingredients' => "Kangkung 1 ikat\nKol 1/4 buah\nTauge 100 gram\nKentang 2 buah\nTelur rebus 2 butir\nTahu goreng 4 potong\nTempe goreng 4 potong\nKacang tanah goreng 200 gram\nCabai rawit 5 buah\nGula merah 2 sdm\nGaram secukupnya\nAir asam jawa",
                'instructions' => "1. Rebus sayuran (kangkung, kol, tauge) hingga matang\n2. Rebus kentang hingga empuk, potong-potong\n3. Haluskan kacang tanah goreng, cabai, gula merah, dan garam\n4. Tambahkan air asam jawa, aduk rata\n5. Tata sayuran, kentang, telur, tahu, dan tempe di piring\n6. Siram dengan bumbu kacang\n7. Sajikan dengan kerupuk",
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80',
                'source' => 'local',
                'is_approved' => true,
            ],
            [
                'user_id' => $chef1->id,
                'title' => 'Mie Goreng',
                'category' => 'Main Course',
                'origin' => 'Indonesia',
                'ingredients' => "Mie kuning 2 bungkus\nTelur 2 butir\nSawi hijau 3 batang\nBawang merah 5 siung\nBawang putih 3 siung\nCabai rawit 5 buah\nKecap manis 2 sdm\nSaus tiram 1 sdm\nGaram secukupnya\nMinyak goreng",
                'instructions' => "1. Rebus mie hingga matang, tiriskan\n2. Tumis bawang merah, bawang putih, dan cabai hingga harum\n3. Masukkan telur, orak-arik\n4. Masukkan sawi, tumis sebentar\n5. Masukkan mie, kecap manis, dan saus tiram\n6. Aduk rata, beri garam secukupnya\n7. Sajikan hangat",
                'image_url' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=600&q=80',
                'source' => 'local',
                'is_approved' => true,
            ],
            [
                'user_id' => $chef2->id,
                'title' => 'Ayam Bakar',
                'category' => 'Main Course',
                'origin' => 'Indonesia',
                'ingredients' => "Ayam 1 ekor\nBawang merah 8 siung\nBawang putih 5 siung\nKemiri 4 butir\nKunyit 2 cm\nJahe 2 cm\nSerai 2 batang\nDaun salam 3 lembar\nKecap manis 3 sdm\nGaram secukupnya\nMinyak goreng",
                'instructions' => "1. Haluskan bawang merah, bawang putih, kemiri, kunyit, dan jahe\n2. Lumuri ayam dengan bumbu halus, kecap manis, dan garam\n3. Diamkan selama 1 jam\n4. Bakar ayam di atas bara api sambil diolesi sisa bumbu\n5. Bakar hingga matang dan berwarna kecoklatan\n6. Sajikan dengan sambal dan lalapan",
                'image_url' => 'https://images.unsplash.com/photo-1598515214211-89d3e73ae83b?auto=format&fit=crop&w=600&q=80',
                'source' => 'local',
                'is_approved' => true,
            ],
            [
                'user_id' => $chef1->id,
                'title' => 'Sop Buntut',
                'category' => 'Soup',
                'origin' => 'Indonesia',
                'ingredients' => "Buntut sapi 1 kg\nWortel 2 buah\nKentang 3 buah\nSeledri 2 batang\nBawang merah goreng\nBawang putih 5 siung\nMerica butiran 1 sdt\nPala bubuk 1/2 sdt\nGaram secukupnya\nMinyak goreng",
                'instructions' => "1. Rebus buntut sapi hingga empuk (bisa menggunakan pressure cooker)\n2. Tumis bawang putih hingga harum\n3. Masukkan tumisan ke dalam kaldu buntut\n4. Tambahkan wortel dan kentang yang sudah dipotong\n5. Beri merica, pala, dan garam\n6. Masak hingga sayuran matang\n7. Sajikan dengan taburan seledri dan bawang goreng",
                'image_url' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=600&q=80',
                'source' => 'local',
                'is_approved' => true,
            ],
            [
                'user_id' => $chef2->id,
                'title' => 'Nasi Uduk',
                'category' => 'Main Course',
                'origin' => 'Jakarta',
                'ingredients' => "Beras 500 gram\nSantan 500 ml\nSerai 2 batang\nDaun salam 3 lembar\nDaun pandan 2 lembar\nBawang merah 3 siung\nBawang putih 2 siung\nGaram secukupnya",
                'instructions' => "1. Cuci beras hingga bersih\n2. Geprek serai, iris bawang merah dan bawang putih\n3. Masukkan beras, santan, serai, daun salam, daun pandan, bawang, dan garam ke dalam rice cooker\n4. Masak hingga matang\n5. Aduk nasi agar bumbu merata\n6. Sajikan dengan lauk pelengkap seperti ayam goreng, telur, dan sambal",
                'image_url' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=600&q=80',
                'source' => 'local',
                'is_approved' => true,
            ],
            [
                'user_id' => $chef1->id,
                'title' => 'Bakso',
                'category' => 'Soup',
                'origin' => 'Indonesia',
                'ingredients' => "Daging sapi giling 500 gram\nTepung tapioka 100 gram\nBawang putih 5 siung\nTelur 1 butir\nGaram secukupnya\nMerica secukupnya\nKaldu sapi 2 liter\nMie kuning\nSawi hijau\nBawang goreng\nSeledri",
                'instructions' => "1. Haluskan daging sapi giling dengan bawang putih\n2. Campurkan dengan tepung tapioka, telur, garam, dan merica\n3. Uleni hingga kalis\n4. Bentuk bulat-bulat, rebus dalam air mendidih hingga mengapung\n5. Rebus mie dan sawi\n6. Tata mie, bakso, dan sawi dalam mangkuk\n7. Siram dengan kaldu panas\n8. Beri taburan bawang goreng dan seledri",
                'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=600&q=80',
                'source' => 'local',
                'is_approved' => true,
            ],
            [
                'user_id' => $chef2->id,
                'title' => 'Opor Ayam',
                'category' => 'Main Course',
                'origin' => 'Jawa Tengah',
                'ingredients' => "Ayam 1 ekor\nSantan kental 500 ml\nBawang merah 8 siung\nBawang putih 5 siung\nKemiri 5 butir\nKunyit 2 cm\nJahe 2 cm\nSerai 2 batang\nDaun salam 3 lembar\nDaun jeruk 5 lembar\nGaram secukupnya\nGula secukupnya",
                'instructions' => "1. Haluskan bawang merah, bawang putih, kemiri, kunyit, dan jahe\n2. Tumis bumbu halus hingga harum\n3. Masukkan ayam, aduk hingga berubah warna\n4. Tuang santan, masukkan serai, daun salam, dan daun jeruk\n5. Masak dengan api kecil hingga ayam matang dan bumbu meresap\n6. Beri garam dan gula secukupnya\n7. Sajikan dengan nasi hangat atau ketupat",
                'image_url' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=600&q=80',
                'source' => 'local',
                'is_approved' => true,
            ],
        ];

        foreach ($recipes as $recipe) {
            Recipe::create($recipe);
        }
    }
}
