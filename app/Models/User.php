<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'active_badge_id',
        'password',
        'role',
        'show_on_leaderboard',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'show_on_leaderboard' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $user->points()->create([
                'points' => 0,
                'level' => 'Koki Pemula'
            ]);
            $user->streak()->create([
                'current_streak' => 0,
                'longest_streak' => 0,
                'last_planned_week' => null
            ]);
        });
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function savedRecipes(): HasMany
    {
        return $this->hasMany(SavedRecipe::class);
    }

    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function recipeViews(): HasMany
    {
        return $this->hasMany(RecipeView::class);
    }

    public function mealPlanTemplates(): HasMany
    {
        return $this->hasMany(MealPlanTemplate::class, 'chef_id');
    }

    public function points(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserPoints::class);
    }

    public function badges(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withPivot('unlocked_at')
                    ->withTimestamps();
    }

    public function streak(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MealPlanStreak::class);
    }

    public function activeBadge(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Badge::class, 'active_badge_id');
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return null;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isChef(): bool
    {
        return $this->role === 'chef';
    }
}
