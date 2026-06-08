<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealPlanTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'chef_id',
        'name',
        'goal',
        'description',
    ];

    public function chef(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chef_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MealPlanTemplateItem::class);
    }
}
