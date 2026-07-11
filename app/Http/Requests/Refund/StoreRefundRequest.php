<?php

namespace App\Http\Requests\Refund;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRefundRequest extends FormRequest
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
            'method' => ['required', Rule::in(PaymentMethod::values())],
            'reason' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sale_item_id' => ['required', 'exists:sale_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.restock' => ['sometimes', 'boolean'],
        ];
    }
}
