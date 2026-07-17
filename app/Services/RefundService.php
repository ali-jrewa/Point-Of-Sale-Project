<?php

namespace App\Services;

use App\Enums\SaleStatus;
use App\Models\Product;
use App\Models\Refund;
use App\Models\RefundItem;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RefundService
{
    public function __construct(private PaymentService $paymentService) {}

    public function store(Sale $sale, array $data): Refund
    {
        return DB::transaction(function () use ($sale, $data) {

            // re-fetch WITH row lock - can't call lockForUpdate() on an already-loaded model
            $sale = Sale::lockForUpdate()->findOrFail($sale->id);

            if (! in_array($sale->sale_status, [
                SaleStatus::Completed,
                SaleStatus::PartiallyRefunded,
            ])) {
                throw ValidationException::withMessages([
                    'sale' => 'This sale is not eligible for refund.',
                ]);
            }

            $itemsValue = 0; // value of goods being returned

            foreach ($data['items'] as $item) {
                $saleItem = $sale->items()->findOrFail($item['sale_item_id']);

                $alreadyRefunded = RefundItem::where('sale_item_id', $saleItem->id)->sum('quantity');
                $availableToRefund = $saleItem->quantity - $alreadyRefunded;

                if ($item['quantity'] <= 0) {
                    throw ValidationException::withMessages([
                        'items' => "Refund quantity must be greater than zero for {$saleItem->product->name}."
                    ]);
                }

                if ($item['quantity'] > $availableToRefund) {
                    throw ValidationException::withMessages([
                        'items' => "Cannot refund more than purchased for {$saleItem->product->name}."
                    ]);
                }

                $unitValue = $saleItem->subtotal / $saleItem->quantity;
                $itemsValue += $unitValue * $item['quantity'];
            }

            // shrink the order's value by what's being returned
            $newTotal = max($sale->total - $itemsValue, 0);

            // cash only refunded if customer had paid more than the new (shrunk) total
            $cashRefund = max($sale->paid_amount - $newTotal, 0);

            $refund = Refund::create([
                'sale_id'       => $sale->id,
                'user_id'       => Auth::id(),
                'refund_code'   => $this->generateRefundCode(),
                'amount'        => $itemsValue,
                'cash_refunded' => $cashRefund,
                'method'        => $data['method'],
                'reason'        => $data['reason'] ?? null,
                'refunded_at'   => now(),
            ]);

            foreach ($data['items'] as $item) {
                $saleItem = $sale->items()->findOrFail($item['sale_item_id']);
                $unitValue = $saleItem->subtotal / $saleItem->quantity;
                $restock = $item['restock'] ?? true;

                RefundItem::create([
                    'refund_id'    => $refund->id,
                    'sale_item_id' => $saleItem->id,
                    'product_id'   => $saleItem->product_id,
                    'quantity'     => $item['quantity'],
                    'amount'       => $unitValue * $item['quantity'],
                    'restocked'    => $restock,
                ]);

                if ($restock) {
                    Product::where('id', $saleItem->product_id)
                        ->lockForUpdate()
                        ->increment('stock_quantity', $item['quantity']);
                }
            }

            // update total FIRST, so paid_amount check has the correct new total to compare against
            $sale->update([
                'total'       => $newTotal,
                'subtotal'    => max($sale->subtotal - $itemsValue, 0),
                'sale_status' => $newTotal <= 0 ? SaleStatus::Refunded : SaleStatus::PartiallyRefunded,
            ]);

            $sale = $sale->fresh();
            $this->paymentService->updateSalePayment($sale);

            return $refund->load('items.product');
        });
    }

    public function destroy(Refund $refund): void
    {
        DB::transaction(function () use ($refund) {

            $sale = $refund->sale()->lockForUpdate()->first();

            foreach ($refund->items as $item) {
                if ($item->restocked) {
                    Product::where('id', $item->product_id)
                        ->decrement('stock_quantity', $item->quantity);
                }
            }

            $itemsValue = $refund->amount;

            $refund->delete();

            // restore the value the refund had removed from the sale
            $sale->update([
                'total'    => $sale->total + $itemsValue,
                'subtotal' => $sale->subtotal + $itemsValue,
            ]);

            $sale = $sale->fresh();
            $this->paymentService->updateSalePayment($sale);

            $sale = $sale->fresh();
            $sale->update([
                'sale_status' => $sale->refunds()->exists()
                    ? SaleStatus::PartiallyRefunded
                    : SaleStatus::Completed,
            ]);
        });
    }

    private function generateRefundCode(): string
    {
        do {
            $code = 'REF-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));
        } while (Refund::where('refund_code', $code)->exists());

        return $code;
    }
}
