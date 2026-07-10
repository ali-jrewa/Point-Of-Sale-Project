<?php

namespace App\Http\Requests\Customer;

use App\Enums\CustomerStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
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

        'first_name' => 'required|string|max:100',

        'last_name' => 'required|string|max:100',

        'company_name' => 'nullable|string|max:150',

        'phone' => 'required|string|max:30|unique:customers,phone',

        'email' => 'nullable|email|max:255|unique:customers,email',

        'date_of_birth' => 'nullable|date',

        'address' => 'nullable|string',

        'credit_limit' => 'required|numeric|min:0',

        'notes' => 'nullable|string',

        'status' => [
            'required',
            Rule::in(CustomerStatus::values()),
        ],

    ];
    }

    public function messages(): array
{
    return [
        'first_name.required' => 'The first name is required.',
        'first_name.string' => 'The first name must be a valid text.',
        'first_name.max' => 'The first name may not be greater than 100 characters.',

        'last_name.required' => 'The last name is required.',
        'last_name.string' => 'The last name must be a valid text.',
        'last_name.max' => 'The last name may not be greater than 100 characters.',

        'company_name.string' => 'The company name must be a valid text.',
        'company_name.max' => 'The company name may not be greater than 150 characters.',

        'phone.required' => 'The phone number is required.',
        'phone.string' => 'The phone number must be a valid text.',
        'phone.max' => 'The phone number may not be greater than 30 characters.',
        'phone.unique' => 'This phone number is already registered.',

        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'The email may not be greater than 255 characters.',
        'email.unique' => 'This email address is already registered.',

        'date_of_birth.date' => 'Please enter a valid date of birth.',

        'address.string' => 'The address must be valid text.',

        'credit_limit.required' => 'The credit limit is required.',
        'credit_limit.numeric' => 'The credit limit must be a valid number.',
        'credit_limit.min' => 'The credit limit cannot be negative.',

        'notes.string' => 'The notes must be valid text.',

        'status.required' => 'Please select a customer status.',
        'status.in' => 'The selected customer status is invalid.',
    ];
    }

    public function attributes(): array
{
    return [
        'first_name' => 'first name',
        'last_name' => 'last name',
        'company_name' => 'company name',
        'phone' => 'phone number',
        'email' => 'email address',
        'date_of_birth' => 'date of birth',
        'credit_limit' => 'credit limit',
    ];
}
}
