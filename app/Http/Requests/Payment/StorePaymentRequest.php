<?php

namespace App\Http\Requests\Payment;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'amount' => 'required|numeric|min:0.01',

            'method' => 'required|in:'.implode(',', PaymentMethod::values()),

            'reference' => 'nullable|string|max:255',

            'notes' => 'nullable|string',

        ];
    }

    public function messages(): array
    {
        return [

            'amount.required' => 'Payment amount is required.',

            'amount.numeric' => 'Payment amount must be numeric.',

            'amount.min' => 'Payment amount must be greater than zero.',

            'method.required' => 'Please select payment method.',

        ];
    }
}
