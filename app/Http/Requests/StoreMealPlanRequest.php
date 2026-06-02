<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMealPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'week_start' => 'required|date',
            'items' => 'nullable|array',
            'items.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'items.*.meal_type' => 'required|in:breakfast,lunch,dinner',
            'items.*.recipe_id' => 'nullable|exists:recipes,id',
            'items.*.meal_api_id' => 'nullable|string',
        ];
    }
}
