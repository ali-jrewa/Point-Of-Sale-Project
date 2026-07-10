<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class  SaleService
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

   public function store(array $data): Sale
    {
        return DB::transaction(function () use ($data) {

        $subtotal = 0;

        foreach ($data['items'] as &$item) {

            $item['discount'] = $item['discount'] ?? 0;
            $item['tax'] = $item['tax'] ?? 0;

            $item['subtotal'] =
                ($item['quantity'] * $item['unit_price'])
                - $item['discount']
                + $item['tax'];

            $subtotal += $item['subtotal'];
            }

            $discount = $data['discount'] ?? 0;

            $tax = $data['tax'] ?? 0;

            $grandTotal = $subtotal - $discount + $tax;

            $sale = Sale::create([

                'sale_code' => $this->generateSaleCode(),

                'customer_id' => $data['customer_id'],

                'user_id' => Auth::id(),

                'invoice_number' => $data['invoice_number'] ?? null,

                'subtotal' => $subtotal,

                'discount' => $discount,

                'tax' => $tax,

                'total' => $grandTotal,

                'paid_amount' => 0,

                'due_amount' => $grandTotal,

                'sale_status' => $data['sale_status'],

                'payment_status' => PaymentStatus::UnPaid,

                'notes' => $data['notes'] ?? null,

                'sold_at' => $data['sold_at'],

            ]);

            foreach ($data['items'] as $item) {

                SaleItem::create([

                    'sale_id' => $sale->id,

                    'product_id' => $item['product_id'],

                    'quantity' => $item['quantity'],

                    'unit_price' => $item['unit_price'],

                    'discount' => $item['discount'],

                    'tax' => $item['tax'],

                    'subtotal' => $item['subtotal'],

                ]);

                Product::where('id', $item['product_id'])
                    ->decrement('stock_quantity', $item['quantity']);
            }

            if (
                isset($data['payment']['amount'])
                && $data['payment']['amount'] > 0
            ) {

                Payment::create([

                    'sale_id' => $sale->id,

                    'user_id' => Auth::id(),

                    'payment_code' => $this->generatePaymentCode(),

                    'method' => $data['payment']['method'],

                    'amount' => $data['payment']['amount'],

                    'reference' => $data['payment']['reference'] ?? null,

                    'notes' => $data['payment']['notes'] ?? null,

                    'paid_at' => now(),

                ]);
            }

            $this->updatePaymentSummary($sale);

            return $sale->fresh([
                'customer',
                'items.product',
                'payments',
            ]);
        });
    }
    protected function updatePaymentSummary(Sale $sale): void
    {
        $paidAmount = $sale->payments()->sum('amount');

        $dueAmount = max(
            0,
            $sale->total - $paidAmount
        );

        if ($paidAmount <= 0) {

            $status = PaymentStatus::UnPaid;

        } elseif ($paidAmount < $sale->total) {

            $status = PaymentStatus::Partial;

        } else {

            $status = PaymentStatus::Paid;
        }

        $sale->update([

            'paid_amount' => $paidAmount,

            'due_amount' => $dueAmount,

            'payment_status' => $status,

        ]);
    }

    protected function generateSaleCode(): string
    {
        do {

            $code = 'SAL-' . strtoupper(Str::random(8));

        } while (Sale::where('sale_code', $code)->exists());

        return $code;
    }

    protected function generatePaymentCode(): string
    {
        do {

            $code = 'PAY-' . strtoupper(Str::random(8));

        } while (Payment::where('payment_code', $code)->exists());

        return $code;
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
