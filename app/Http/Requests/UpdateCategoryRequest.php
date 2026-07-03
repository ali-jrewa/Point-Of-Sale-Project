<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,

            'code' => 'nullable|string|max:50',

            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The category name is required.',
            'name.string' => 'The category name must be a valid text.',
            'name.max' => 'The category name may not be greater than 255 characters.',

            'description.string' => 'The description must be valid text.',

            'slug.required' => 'The slug is required.',
            'slug.string' => 'The slug must be valid text.',
            'slug.max' => 'The slug may not be greater than 255 characters.',
            'slug.unique' => 'This slug is already being used by another category.',

            'code.string' => 'The category code must be valid text.',
            'code.max' => 'The category code may not be greater than 50 characters.',

            'is_active.boolean' => 'The active status is invalid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'category name',
            'description' => 'description',
            'slug' => 'slug',
            'code' => 'category code',
            'is_active' => 'active status',
        ];
    }
}
