<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealPlanTemplateItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_plan_template_id',
        'day_of_week',
        'meal_time',
        'recipe_id',
        'meal_api_id',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(MealPlanTemplate::class, 'meal_plan_template_id');
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
