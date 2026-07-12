<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  CustomerService
{
    public function addCredit(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'credit_limit' => 'required|numeric|min:0',
        ]);

        $customer->credit_limit += $validated['credit_limit'];
        $customer->save();

    }

     public function getPaginatedLinks()
    {
        return Customer::latest()->paginate(10);
    }

    public function search(?string $search)
    {
    return Customer::query()

        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('customer_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhereRaw(
                        "CONCAT(first_name, ' ', last_name) LIKE ?",
                        ["%{$search}%"]
                    );

            });

        })

        ->latest()

        ->get();
    }
    public function store(array $data): Customer
    {
        return DB::transaction(function () use ($data) {

            $data['customer_code'] = $this->generateCustomerCode();

            $data['created_by'] = Auth::id();

            return Customer::create($data);

        });
    }

    public function update(Customer $customer, array $data): Customer
    {
        return DB::transaction(function () use ($customer, $data) {
            $data['updated_by'] = Auth::id();

            $customer->update($data);

            return $customer;

        });
    }

    public function delete(Customer $customer): void
    {
        $customer->delete();
    }

    protected function generateCustomerCode(): string
    {
        $lastCustomer = Customer::withTrashed()
            ->latest('id')
            ->first();

        $nextNumber = $lastCustomer
            ? $lastCustomer->id + 1
            : 1;

        return 'CUS-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

}
