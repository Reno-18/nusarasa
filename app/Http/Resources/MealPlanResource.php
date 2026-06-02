<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'week_start' => $this->week_start?->toDateString(),
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'day_of_week' => $item->day_of_week,
                    'meal_type' => $item->meal_type,
                    'recipe_id' => $item->recipe_id,
                    'recipe_title' => $item->recipe->title ?? null,
                    'meal_api_id' => $item->meal_api_id,
                ];
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
