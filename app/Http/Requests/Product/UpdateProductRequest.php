<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(ProductStatus::values())],
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $this->route('product')->id,
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $this->route('product')->id,
            'low_stock_threshold' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [

            'category_id' => [
                'required',
                'exists:categories,id'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'cost_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'retail_price' => [
                'required',
                'numeric',
                'gte:cost_price'
            ],

            'sku' => [
                'nullable',
                'string',
                'max:100',
                'unique:products,sku'
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:100',
                'unique:products,barcode'
            ],

            'low_stock_threshold' => [
                'required',
                'integer',
                'min:0'
            ],

            'status' => [
                'required',
                Rule::in(ProductStatus::values())
            ]
        ];
    }
}
