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

            $totalRefundAmount = 0;

            // Validate quantities first, before creating anything
            foreach ($data['items'] as $item) {

                $saleItem = $sale->items()->findOrFail($item['sale_item_id']);

                $alreadyRefunded = RefundItem::where('sale_item_id', $saleItem->id)
                    ->sum('quantity');

                $availableToRefund = $saleItem->quantity - $alreadyRefunded;

                if ($item['quantity'] > $availableToRefund) {
                    throw ValidationException::withMessages([
                        'items' => "Cannot refund more than purchased for {$saleItem->product->name}."
                    ]);
                }

                $unitRefund = $saleItem->subtotal / $saleItem->quantity;
                $totalRefundAmount += $unitRefund * $item['quantity'];
            }

            if ($totalRefundAmount > $sale->paid_amount) {
                throw ValidationException::withMessages([
                    'amount' => 'Refund amount cannot exceed amount paid.'
                ]);
            }

            $refund = Refund::create([
                'sale_id'     => $sale->id,
                'user_id'     => Auth::id(),
                'refund_code' => $this->generateRefundCode(),
                'amount'      => $totalRefundAmount,
                'method'      => $data['method'],
                'reason'      => $data['reason'] ?? null,
                'refunded_at' => now(),
            ]);

            foreach ($data['items'] as $item) {

                $saleItem = $sale->items()->findOrFail($item['sale_item_id']);
                $unitRefund = $saleItem->subtotal / $saleItem->quantity;
                $restock = $item['restock'] ?? true;

                RefundItem::create([
                    'refund_id'    => $refund->id,
                    'sale_item_id' => $saleItem->id,
                    'product_id'   => $saleItem->product_id,
                    'quantity'     => $item['quantity'],
                    'amount'       => $unitRefund * $item['quantity'],
                    'restocked'    => $restock,
                ]);

                if ($restock) {
                    Product::where('id', $saleItem->product_id)
                        ->lockForUpdate()
                        ->increment('stock_quantity', $item['quantity']);
                }
            }

            // Recalculate paid/due/payment_status net of refunds
            $this->paymentService->updateSalePayment($sale->fresh());

            $totalRefunded = $sale->refunds()->sum('amount');

            $sale->update([
                'sale_status' => $totalRefunded >= $sale->total
                    ? SaleStatus::Refunded
                    : SaleStatus::PartiallyRefunded,
            ]);

            return $refund->load('items.product');
        });
    }

    public function destroy(Refund $refund): void
    {
        DB::transaction(function () use ($refund) {

            $sale = $refund->sale;

            foreach ($refund->items as $item) {
                if ($item->restocked) {
                    Product::where('id', $item->product_id)
                        ->decrement('stock_quantity', $item->quantity);
                }
            }

            $refund->delete();

            $this->paymentService->updateSalePayment($sale->fresh());

            $totalRefunded = $sale->refunds()->sum('amount');

            $sale->update([
                'sale_status' => $totalRefunded == 0
                    ? SaleStatus::Completed
                    : SaleStatus::PartiallyRefunded,
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
