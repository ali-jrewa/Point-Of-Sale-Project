<?php

namespace App\Http\Requests\Sale;

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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

            'customer_id' => 'nullable|exists:customers,id',

            'invoice_number' => 'nullable|string|max:100',

            'sold_at' => 'required|date',

            'sale_status' => 'required|in:'.implode(',',SaleStatus::values()),

            'discount' => 'nullable|numeric|min:0',

            'tax' => 'nullable|numeric|min:0',

            'notes' => 'nullable|string',

            'payment.amount' => 'nullable|numeric|min:0',

            'payment.method' => 'nullable|in:'.implode(',',PaymentMethod::values()),

            'payment.reference' => 'nullable|string|max:255',

            'payment.notes' => 'nullable|string',

            'items' => 'required|array|min:1',

            'items.*.product_id' => 'required|exists:products,id',

            'items.*.quantity' => 'required|integer|min:1',

            'items.*.unit_price' => 'required|numeric|min:0',

            'items.*.discount' => 'nullable|numeric|min:0',

            'items.*.tax' => 'nullable|numeric|min:0',

        ];
    }

    public function messages(): array
    {
        return [

            'customer_id.exists' => 'Selected customer does not exist.',

            'invoice_number.max' => 'Invoice number may not exceed 100 characters.',

            'sale_status.required' => 'Sale status is required.',

            'sold_at.required' => 'Sale date is required.',

            'sold_at.date' => 'Sale date is invalid.',

            'items.required' => 'Please add at least one product.',

            'items.array' => 'Products format is invalid.',

            'items.min' => 'Please add at least one product.',

            'items.*.product_id.required' => 'Please select a product.',

            'items.*.product_id.exists' => 'Selected product does not exist.',

            'items.*.quantity.required' => 'Quantity is required.',

            'items.*.quantity.integer' => 'Quantity must be an integer.',

            'items.*.quantity.min' => 'Quantity must be at least 1.',

            'items.*.unit_price.required' => 'Unit price is required.',

            'items.*.unit_price.numeric' => 'Unit price must be numeric.',

            'items.*.discount.numeric' => 'Discount must be numeric.',

            'items.*.tax.numeric' => 'Tax must be numeric.',

        ];
    }
}
