<?php

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  SupplierService
{
     public function getPaginatedLinks()
    {
        return Supplier::latest()->paginate(10);
    }

    public function search(?string $search)
    {
    return Supplier::query()

        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('first_name', 'like', "%{$search}%")
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
    public function store(array $data): Supplier
    {
        return DB::transaction(function () use ($data) {


            $data['created_by'] = Auth::id();

            return Supplier::create($data);

        });
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        return DB::transaction(function () use ($supplier, $data) {
            $data['updated_by'] = Auth::id();

            $supplier->update($data);

            return $supplier;

        });
    }

    public function delete(Supplier $supplier): void
    {

        DB::transaction(function () use ($supplier) {

        foreach ($supplier->purchases as $purchase) {
            $purchase->update([
                'supplier_id' => null,

                'notes' => trim(($purchase->notes ?? '') . " [Deleted Supplier: " . $supplier->first_name . "]"),
            ]);
        }

        $supplier->delete();
    });
    }

}
