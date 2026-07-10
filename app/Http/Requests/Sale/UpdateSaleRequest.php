<?php

namespace App\Http\Requests\Sale;

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;

class UpdateSaleRequest extends StoreSaleRequest
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
}
