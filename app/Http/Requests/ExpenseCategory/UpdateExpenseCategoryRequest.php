<?php

namespace App\Http\Requests\ExpenseCategory;

use App\Enums\ExpenseCategoryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenseCategoryRequest extends FormRequest
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
        $expenseCategory=$this->route('expense_category');

        return [

            'name'=>[
                'required',
                'string',
                'max:100',
                Rule::unique('expense_categories')
                    ->ignore($expenseCategory)
            ],

            'code'=>[
                'nullable',
                'string',
                'max:30',
                Rule::unique('expense_categories')
                    ->ignore($expenseCategory)
            ],

            'description'=>'nullable|string',

            'status'=>[
                'required',
                Rule::in(ExpenseCategoryStatus::values())
            ]

        ];
    }

    public function messages(): array
    {
        return [

            'name.required'=>'The expense category name is required.',

            'name.unique'=>'This expense category already exists.',

            'code.unique'=>'This expense category code already exists.',

            'status.required'=>'Please select a status.',

            'status.in'=>'The selected status is invalid.',

        ];
    }
}
