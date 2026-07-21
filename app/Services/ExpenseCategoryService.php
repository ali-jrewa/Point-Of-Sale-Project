<?php

namespace App\Services;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;

class  ExpenseCategoryService
{
    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function search(?string $search)
    {
        return ExpenseCategory::query()

            ->when($search,function($query) use($search){

                $query->where(function($q) use($search){

                    $q->where('name','like',"%{$search}%")

                        ->orWhere('code','like',"%{$search}%");

                });

            })

            ->latest()

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(array $data): ExpenseCategory
    {
        return DB::transaction(function() use($data){

            if(empty($data['code'])){

                $data['code']=$this->generateCode();

            }

            return ExpenseCategory::create($data);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        ExpenseCategory $expenseCategory,
        array $data
    ): ExpenseCategory
    {
        return DB::transaction(function() use(
            $expenseCategory,
            $data
        ){

            $expenseCategory->update($data);

            return $expenseCategory;

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(
        ExpenseCategory $expenseCategory
    ):void
    {
        $expenseCategory->forceDelete();
    }

    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */

    public function restore(int $id):void
    {
        ExpenseCategory::onlyTrashed()

            ->findOrFail($id)

            ->restore();
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Code
    |--------------------------------------------------------------------------
    */

    private function generateCode(): string
{
    $last = ExpenseCategory::withTrashed()
        ->latest('id')
        ->first();

    $next = $last ? $last->id + 1 : 1;

    return 'EXPCAT-' . str_pad($next, 6, '0', STR_PAD_LEFT);
}
}
