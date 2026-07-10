<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseCategory\StoreExpenseCategoryRequest;
use App\Http\Requests\ExpenseCategory\UpdateExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use App\Services\ExpenseCategoryService;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
     public function __construct(
        protected ExpenseCategoryService $expenseCategoryService){}

    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        return view('expense-category.list');
    }

    public function getExpenseCategories(Request $request)
    {
        return response()->json(

            $this->expenseCategoryService
                ->search($request->search)

        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseCategoryRequest $request)
    {
        $this->expenseCategoryService
            ->store($request->validated());

        return response()->json([
            'success'=>'Expense category created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit(ExpenseCategory $expenseCategory)
    {
        return response()->json($expenseCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateExpenseCategoryRequest $request,
        ExpenseCategory $expenseCategory
    )
    {
        $this->expenseCategoryService->update(
            $expenseCategory,
            $request->validated()
        );

        return response()->json([
            'success'=>'Expense category updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        ExpenseCategory $expenseCategory
    )
    {
        $this->expenseCategoryService
            ->delete($expenseCategory);

        return response()->json([
            'success'=>'Expense category deleted successfully.'
        ]);
    }

    public function restore($id)
    {
        $this->expenseCategoryService
            ->restore($id);

        return response()->json([
            'success'=>'Expense category restored successfully.'
        ]);
    }
}
