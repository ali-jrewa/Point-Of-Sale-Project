<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Services\ExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(protected ExpenseService $expenseService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expenseCategories = ExpenseCategory::all();

        $expenses = $this->expenseService->getPaginatedLinks();

        return view('expense.list', compact('expenses','expenseCategories'));
    }

    public function getExpenses(Request $request)
    {
        $request->validate([
            'search' => 'string|max:50|nullable',
            'expense_date' => 'date|nullable'
        ]);

        $expenses = $this->expenseService
            ->search($request->search , $request->expense_date);

        return response()->json($expenses);
    }


    public function store(StoreExpenseRequest $request)
    {
        $this->expenseService->store($request->validated());

        return response()->json(['success' => 'Expense created successfully.'], 201);
    }

    public function edit(Expense $expense)
    {
        return response()->json($expense);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
    $this->expenseService->update($expense, $request->validated());

    return response()->json([
        'success' => 'Expense updated successfully.'
    ]);
    }

    public function destroy(Expense $expense)
    {
        $this->expenseService->delete($expense);

        return response()->json([
            'success' => 'Expense deleted successfully.'
        ]);
    }
}
