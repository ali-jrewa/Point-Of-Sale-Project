<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  PurchaseService
{

    public function search(?string $search,?string $purchaseDate,int $perPage = 10)
        {
        return Purchase::with(['supplier','user'])

        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('purchase_code','like',"%{$search}%")
                ->orWhere('invoice_number','like',"%{$search}%")
                ->orWhere('purchase_status','like',"%{$search}%")
                ->orWhere('payment_status','like',"%{$search}%")
                ->orWhereHas('supplier',function($supplier) use ($search){

                    $supplier->withTrashed()->where(function($q) use($search){

                        $q->where('first_name','like',"%{$search}%")
                        ->orWhere('last_name','like',"%{$search}%");

                    });

                });

            });

        })

        ->when($purchaseDate,function($query) use($purchaseDate){

            $query->whereDate(
                'purchased_at',
                $purchaseDate
            );

        })

        ->latest()
        ->paginate($perPage);
    }

    public function show(int $id): Purchase
    {
        return Purchase::with([
            'supplier',
            'user',
            'items.product'
        ])->findOrFail($id);
    }

    public function store(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {

            $subtotal = 0;

            foreach ($data['items'] as $item) {

                $subtotal +=
                    ($item['quantity'] * $item['unit_cost'])
                    - ($item['discount'] ?? 0)
                    + ($item['tax'] ?? 0);
            }

            $discount = $data['discount'] ?? 0;
            $tax = $data['tax'] ?? 0;

            $total = ($subtotal - $discount) + $tax;

            /*
            |----------------------------------------------------
            | Create Purchase First
            |----------------------------------------------------
            */

            $purchase = Purchase::create([

                'purchase_code' => '',

                'supplier_id' => $data['supplier_id'],

                'user_id' => Auth::id(),

                'invoice_number' => $data['invoice_number'] ?? null,

                'subtotal' => $subtotal,

                'discount' => $data['discount'],

                'tax' => $data['tax'],

                'total' => $total,

                'purchase_status' => $data['purchase_status'],

                'payment_status' => $data['payment_status'],

                'notes' => $data['notes'] ?? null,

                'purchased_at' => $data['purchased_at'],

            ]);

            /*
            |----------------------------------------------------
            | Generate Purchase Code
            |----------------------------------------------------
            */

            $purchase->update([
                'purchase_code' => $this->generatePurchaseCode($purchase)
            ]);

            //Save Purchase Items

            foreach ($data['items'] as $item) {

                $lineSubtotal =
                    ($item['quantity'] * $item['unit_cost'])
                    - ($item['discount'] ?? 0)
                    + ($item['tax'] ?? 0);

                $purchase->items()->create([

                    'product_id' => $item['product_id'],

                    'quantity' => $item['quantity'],

                    'unit_cost' => $item['unit_cost'],

                    'discount' => $item['discount'] ?? 0,

                    'tax' => $item['tax'] ?? 0,

                    'subtotal' => $lineSubtotal,

                ]);

                $product = Product::findOrFail($item['product_id']);

                $product->increment('stock_quantity', $item['quantity']);
            }

            return $purchase;
        });
    }

    public function update(Purchase $purchase, array $data): Purchase
    {
        return DB::transaction(function () use ($purchase, $data) {

            /*
            |----------------------------------------------------
            | Restore Old Stock
            |----------------------------------------------------
            */

            foreach ($purchase->items as $oldItem) {

                $product = Product::findOrFail($oldItem['product_id']);

                $product->decrement('stock_quantity', $oldItem['quantity']);
            }

            /*
            |----------------------------------------------------
            | Delete Old Purchase Items
            |----------------------------------------------------
            */

            $purchase->items()->delete();

            /*
            |----------------------------------------------------
            | Recalculate Totals
            |----------------------------------------------------
            */

            $subtotal = 0;

            foreach ($data['items'] as $item) {

                $subtotal +=
                    ($item['quantity'] * $item['unit_cost'])
                    - ($item['discount'] ?? 0)
                    + ($item['tax'] ?? 0);
            }

            $discount = $data['discount'] ?? 0;

            $tax = $data['tax'] ?? 0;

            $total = ($subtotal - $discount) + $tax;

            /*
            |----------------------------------------------------
            | Update Purchase
            |----------------------------------------------------
            */

            $purchase->update([

                'supplier_id' => $data['supplier_id'],

                'invoice_number' => $data['invoice_number'] ?? null,

                'subtotal' => $subtotal,

                'discount' => $discount,

                'tax' => $tax,

                'total' => $total,

                'purchase_status' => $data['purchase_status'],

                'payment_status' => $data['payment_status'],

                'notes' => $data['notes'] ?? null,

                'purchased_at' => $data['purchased_at'],

            ]);

            /*
            |----------------------------------------------------
            | Create New Purchase Items
            |----------------------------------------------------
            */

            foreach ($data['items'] as $item) {

                $lineSubtotal =
                    ($item['quantity'] * $item['unit_cost'])
                    - ($item['discount'] ?? 0)
                    + ($item['tax'] ?? 0);

                $purchase->items()->create([

                    'product_id' => $item['product_id'],

                    'quantity' => $item['quantity'],

                    'unit_cost' => $item['unit_cost'],

                    'discount' => $item['discount'] ?? 0,

                    'tax' => $item['tax'] ?? 0,

                    'subtotal' => $lineSubtotal,

                ]);

                $product = Product::findOrFail($item['product_id']);

                $product->increment('stock_quantity', $item['quantity']);
            }

            return $purchase;
        });
    }

     public function delete(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase) {

            foreach ($purchase->items as $item) {

                $product = Product::findOrFail($item['product_id']);

                $product->decrement('stock_quantity', $item['quantity']);
            }

            $purchase->items()->delete();

            $purchase->delete();
        });
    }



    protected function generatePurchaseCode(Purchase $purchase): string
    {
        return 'PUR-' . str_pad(
            $purchase->id,
            6,
            '0',
            STR_PAD_LEFT
        );
    }

}
