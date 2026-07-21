<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  ExpenseService
{
     public function getPaginatedLinks()
    {
        return Expense::with('category')->latest()->paginate(10);
    }

    public function search(?string $search, ?string $expenseDate)
    {
    return Expense::with('category')


            ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('expense_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%")
                  ->orWhere('receipt_number', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");

            });

        })

        ->when($expenseDate, function ($query) use ($expenseDate) {

            $query->whereDate('expense_date', $expenseDate);

        })

        ->latest()

        ->get();
    }

    public function store(array $data): Expense
    {
        return DB::transaction(function () use ($data) {

            $data['expense_number'] = $this->generateExpenseNumber();

            $data['created_by'] = Auth::id();

            return Expense::create($data);

    });
    }

    public function update(Expense $expense, array $data): Expense
    {
        return DB::transaction(function () use ($expense, $data) {

        $data['updated_by'] = Auth::id();

        $expense->update($data);

        return $expense;

    });
    }

    public function delete(Expense $expense):void
    {
        $expense->forceDelete();
    }

    public function restore(int $id): void
    {
        Expense::onlyTrashed()
            ->findOrFail($id)
            ->restore();
    }

    protected function generateExpenseNumber(): string
{
    $next = (Expense::max('id') ?? 0) + 1;

    return 'EXP-' . str_pad($next, 6, '0', STR_PAD_LEFT);
}

}
