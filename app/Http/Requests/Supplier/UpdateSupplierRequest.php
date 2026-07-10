<?php

namespace App\Http\Requests\Supplier;

use App\Enums\SupplierStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
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

        'phone' => [
            'required',
            'string',
            'max:30',
            Rule::unique('suppliers')
                ->ignore($this->supplier),
        ],

        'email' => [
            'nullable',
            'email',
            Rule::unique('suppliers')
                ->ignore($this->supplier),
        ],


        'address' => 'nullable|string',

        'tax_number' => 'nullable|string|min:5',

        'status' => [
            'required',
            Rule::in(SupplierStatus::values()),
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
            'phone.string' => 'The phone number must be valid text.',
            'phone.max' => 'The phone number may not be greater than 30 characters.',
            'phone.unique' => 'This phone number is already in use by another customer.',

            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.unique' => 'This email address is already in use by another customer.',

            'address.string' => 'The address must be valid text.',

            'tax_number.string' => 'The tax number must be a valid string.',
            'tax_number.min' => 'The tax number must be more than 4 characters.',

            'status.required' => 'Please select a supplier status.',
            'status.in' => 'The selected supplier status is invalid.',
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
        'tax_number' => 'tax number',
    ];
}
}
