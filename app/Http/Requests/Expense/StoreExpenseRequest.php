<?php

namespace App\Http\Requests\Expense;

use App\Enums\ExpenseStatus;
use App\Enums\PaymentMethodStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
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

            'expense_category_id' => [
                'required',
                'exists:expense_categories,id'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'expense_date' => [
                'required',
                'date'
            ],

            'payment_method' => [
                'required',
                Rule::in(PaymentMethodStatus::values())
            ],

            'vendor_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'receipt_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'reference_no' => [
                'nullable',
                'string',
                'max:100'
            ],

            'status' => [
                'required',
                Rule::in(ExpenseStatus::values())
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'expense_category_id.required' => 'Please select an expense category.',
            'expense_category_id.exists' => 'The selected expense category is invalid.',

            'title.required' => 'Expense title is required.',

            'amount.required' => 'Expense amount is required.',
            'amount.numeric' => 'Amount must be numeric.',
            'amount.min' => 'Amount must be greater than zero.',

            'expense_date.required' => 'Expense date is required.',

            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Invalid payment method.',

            'status.required' => 'Please select a status.',
            'status.in' => 'Invalid expense status.',

        ];
    }
}
