<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
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

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'account_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',

            ],

        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'account_image.image' => 'The file must be an image.',
            'account_image.mimes' => 'Only JPEG, PNG, JPG, and WEBP images are allowed.',
            'account_image.max' => 'The image size must not exceed 2MB.',

        ];
    }
}
