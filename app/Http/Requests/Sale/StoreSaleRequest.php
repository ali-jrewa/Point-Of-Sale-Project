<?php

namespace App\Http\Requests\Sale;

use App\Enums\PaymentMethod;
use App\Enums\SaleStatus;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }



    public function rules(): array
    {

        return [

            /*
            |--------------------------------------------------------------------------
            | Sale Information
            |--------------------------------------------------------------------------
            */

            'customer_id' => [
                'nullable',
                'exists:customers,id'
            ],


            'invoice_number' => [
                'nullable',
                'string',
                'max:100'
            ],


            'sold_at' => [
                'required',
                'date'
            ],



            'sale_status' => [
                'nullable',
                'in:'.implode(',',SaleStatus::values())
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



            'notes'=>[
                'nullable',
                'string'
            ],




            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */


            'payment.amount'=>[

                'nullable',
                'numeric',
                'min:0'

            ],



            'payment.method'=>[

                'required_with:payment.amount',
                'in:'.implode(',',PaymentMethod::values())

            ],



            'payment.reference'=>[

                'nullable',
                'string',
                'max:255'

            ],



            'payment.notes'=>[

                'nullable',
                'string'

            ],




            /*
            |--------------------------------------------------------------------------
            | Products
            |--------------------------------------------------------------------------
            */


            'items'=>[

                'required',
                'array',
                'min:1'

            ],



            'items.*.product_id'=>[

                'required',
                'exists:products,id'

            ],



            'items.*.quantity'=>[

                'required',
                'integer',
                'min:1'

            ],



            'items.*.unit_price'=>[

                'required',
                'numeric',
                'min:0'

            ],



            'items.*.discount'=>[

                'nullable',
                'numeric',
                'min:0'

            ],



            'items.*.tax'=>[

                'nullable',
                'numeric',
                'min:0'

            ],



        ];

    }



    public function messages(): array
    {

        return [

            'customer_id.exists'
            =>
            'Selected customer does not exist.',


            'sold_at.required'
            =>
            'Sale date is required.',



            'sale_status.required'
            =>
            'Sale status is required.',



            'payment.amount.numeric'
            =>
            'Payment amount must be numeric.',



            'payment.method.required_with'
            =>
            'Payment method is required when payment amount exists.',



            'items.required'
            =>
            'Please add at least one product.',



            'items.min'
            =>
            'Please add at least one product.',



            'items.*.product_id.required'
            =>
            'Product is required.',



            'items.*.quantity.required'
            =>
            'Product quantity is required.',



            'items.*.quantity.min'
            =>
            'Quantity must be at least 1.',



            'items.*.unit_price.required'
            =>
            'Product price is required.',


        ];

    }

}
