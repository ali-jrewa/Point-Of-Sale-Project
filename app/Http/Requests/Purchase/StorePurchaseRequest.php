<?php

namespace App\Http\Requests\Purchase;

use App\Enums\PaymentStatus;
use App\Enums\PurchaseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePurchaseRequest extends FormRequest
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

            'supplier_id' => [
                'required',
                'exists:suppliers,id',
            ],

            'invoice_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'purchase_status' => [
                'required',
                Rule::in(PurchaseStatus::values()),
            ],

            'payment_status' => [
                'required',
                Rule::in(PaymentStatus::values()),
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'purchased_at' => [
                'required',
                'date',
            ],
            'discount' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'tax' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            /*
            |--------------------------------------------------------------------------
            | Purchase Items
            |--------------------------------------------------------------------------
            */

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.unit_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'supplier_id.required' => 'Supplier is required.',
            'supplier_id.exists' => 'Selected supplier does not exist.',

            'purchase_status.required' => 'Purchase status is required.',
            'payment_status.required' => 'Payment status is required.',

            'purchased_at.required' => 'Purchase date is required.',
            'purchased_at.date' => 'Purchase date must be valid.',

            'items.required' => 'At least one product is required.',
            'items.array' => 'Items must be an array.',
            'items.min' => 'At least one product must be added.',

            'items.*.product_id.required' => 'Product is required.',
            'items.*.product_id.exists' => 'Selected product does not exist.',

            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.integer' => 'Quantity must be an integer.',
            'items.*.quantity.min' => 'Quantity must be greater than zero.',

            'items.*.unit_cost.required' => 'Unit cost is required.',
            'items.*.unit_cost.numeric' => 'Unit cost must be numeric.',

            'items.*.discount.numeric' => 'Discount must be numeric.',

            'items.*.tax.numeric' => 'Tax must be numeric.',
        ];
    }
}
