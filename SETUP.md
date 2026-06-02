# NusaRasa - Setup Guide

## Sistem Resep Masakan & Meal Planner

### Tech Stack
- Laravel 12
- MySQL
- Laravel Breeze (Blade + Alpine.js)
- Laravel Sanctum (API)
- Tailwind CSS v4
- jQuery (AJAX)
- TheMealDB API (External)

---

## Langkah Setup

### 1. Pastikan MySQL Running
Jalankan MySQL melalui Laragon atau XAMPP. Pastikan MySQL berjalan di port 3306.

### 2. Buat Database
```sql
CREATE DATABASE nusarasa;
```

### 3. Install Dependencies
```bash
composer install
npm install
```

### 4. Setup Environment
File `.env` sudah dikonfigurasi. Pastikan:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nusarasa
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://nusarasa.test
```

### 5. Generate Application Key (jika belum)
```bash
php artisan key:generate
```

### 6. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```

### 7. Create Storage Link
```bash
php artisan storage:link
```

### 8. Build Assets
```bash
npm run build
```

### 9. Run Development Server
```bash
php artisan serve
```

Atau akses melalui Laragon: `http://nusarasa.test`

---

## Default Users

Setelah seeding, gunakan akun berikut untuk login:

### Admin
- Email: `admin@nusarasa.com`
- Password: `password`

### Chef 1
- Email: `chef1@nusarasa.com`
- Password: `password`

### Chef 2
- Email: `chef2@nusarasa.com`
- Password: `password`

### Regular Users
- Email: `user1@nusarasa.com` / `user2@nusarasa.com` / `user3@nusarasa.com`
- Password: `password`

---

## Fitur Utama

### Public
- Browse resep (lokal + API TheMealDB)
- Search & filter resep
- Lihat detail resep
- Lihat rating & komentar

### Authenticated Users
- Simpan resep favorit
- Buat meal plan mingguan
- Beri rating & komentar pada resep

### Chef
- Dashboard dengan statistik
- Tambah, edit, hapus resep sendiri
- Lihat status approval resep

### Admin
- Dashboard admin
- Approve/reject resep
- Kelola users (ubah role)
- Hapus resep

---

## API Endpoints

### Public
- `GET /api/recipes` - List resep
- `GET /api/recipes/{id}` - Detail resep
- `GET /api/recipes/{id}/ratings` - List rating

### Authenticated (Chef/Admin)
- `POST /api/recipes` - Tambah resep
- `PUT /api/recipes/{id}` - Update resep
- `DELETE /api/recipes/{id}` - Hapus resep

### Authenticated (Admin)
- `PATCH /api/recipes/{id}/approve` - Approve resep

### Authenticated (All Users)
- `POST /api/recipes/{id}/ratings` - Tambah rating
- `GET /api/meal-plans` - List meal plans
- `POST /api/meal-plans` - Tambah meal plan
- `POST /api/saved-recipes` - Simpan resep
- `DELETE /api/saved-recipes/{id}` - Hapus saved recipe

---

## AJAX Features

### 1. Live Search
- Ketik di search box untuk mencari resep secara real-time
- Menggabungkan hasil dari database lokal dan TheMealDB API

### 2. Save Recipe
- Klik tombol ♥ untuk menyimpan resep
- Bekerja untuk resep lokal dan API

### 3. Add to Meal Plan
- Tambahkan resep ke meal plan dari halaman detail
- Pilih hari dan waktu makan

### 4. Rating System
- Beri rating 1-5 bintang
- Tambahkan komentar (opsional)

---

## Testing

### Test Routes
```bash
php artisan route:list
```

### Test API dengan Postman/Insomnia
1. Login untuk mendapatkan token (jika menggunakan Sanctum)
2. Test endpoint API sesuai dokumentasi di atas

---

## Troubleshooting

### Error: "could not find driver"
Install PHP MySQL extension di php.ini:
```
extension=pdo_mysql
extension=mysqli
```

### Error: "No connection could be made"
Pastikan MySQL running di Laragon/XAMPP

### Error: Storage link
```bash
php artisan storage:link
```

### Clear Cache
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/          # API Controllers
│   │   ├── Auth/         # Authentication
│   │   ├── Admin/        # Admin Controllers
│   │   └── Chef/         # Chef Controllers
│   ├── Middleware/
│   │   └── CheckRole.php # Role-based access
│   ├── Requests/         # Form Requests
│   └── Resources/        # API Resources
├── Models/               # Eloquent Models
├── Policies/             # Authorization Policies
├── Services/
│   └── MealDbService.php # External API Service
└── Traits/
    └── ApiResponse.php   # API Response Helper

resources/
├── views/
│   ├── recipes/          # Recipe views
│   ├── saved/            # Saved recipes
│   ├── meal-plan/        # Meal planner
│   ├── chef/             # Chef dashboard
│   └── admin/            # Admin dashboard
└── js/
    └── nusarasa.js       # AJAX functions

public/
└── js/
    └── nusarasa.js       # Compiled AJAX

routes/
├── web.php               # Web routes
└── api.php               # API routes
```

---

## Credits

- Laravel Framework
- TheMealDB API (https://www.themealdb.com/)
- Tailwind CSS
- jQuery

---

## License

This project is for educational purposes.
