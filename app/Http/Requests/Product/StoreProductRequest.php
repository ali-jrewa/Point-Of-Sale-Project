<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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

    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'name.string' => 'The product name must be a string.',
            'name.max' => 'The product name may not be greater than 255 characters.',
            'description.string' => 'The description must be a string.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.',
            'stock_quantity.required' => 'The stock quantity is required.',
            'stock_quantity.integer' => 'The stock quantity must be an integer.',
            'stock_quantity.min' => 'The stock quantity must be at least 0.',
        ];
    }
}
