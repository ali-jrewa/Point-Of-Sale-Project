<?php

namespace App\Http\Requests\Category;

use App\Enums\CategoryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name',
            'code' => 'nullable|string|max:255|unique:categories,code',
            'description' => 'nullable|string',
            'status' => [
                'required',
                Rule::in(CategoryStatus::values())
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The category name is required.',
            'name.unique' => 'The category name must be unique.',
            'code.unique' => 'The category code must be unique.',
            'status.in' => 'The selected category status is invalid.',
        ];
    }
}
