<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
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
            'user_name' => $this->user->name ?? null,
            'title' => $this->title,
            'category' => $this->category,
            'origin' => $this->origin,
            'ingredients' => $this->ingredients,
            'instructions' => $this->instructions,
            'image_url' => $this->image_url,
            'source' => $this->source,
            'is_approved' => $this->is_approved,
            'view_count' => $this->view_count,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
