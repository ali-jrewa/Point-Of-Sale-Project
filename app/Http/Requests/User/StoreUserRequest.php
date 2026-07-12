<?php

namespace App\Http\Requests\User;

use App\Enums\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
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

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'status' => [
                'required',
                new Enum(UserStatus::class),
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'role_id.exists' => 'Selected role is invalid.',
            'role_id.required' => 'Role is required.',

            'name.required' => 'Name is required.',

            'email.required' => 'Email is required.',
            'email.email' => 'Email must be valid.',
            'email.unique' => 'Email already exists.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',

            'status.required' => 'Status is required.',
        ];
    }
}
