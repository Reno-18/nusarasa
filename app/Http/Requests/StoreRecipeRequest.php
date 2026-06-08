<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'origin' => 'nullable|string|max:100',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'difficulty' => 'nullable|string',
            'nutrition' => 'nullable|array',
            'tags' => 'nullable|array',
        ];
    }
}
