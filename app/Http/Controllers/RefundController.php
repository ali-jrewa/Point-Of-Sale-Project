<?php

namespace App\Http\Controllers;

use App\Http\Requests\Refund\StoreRefundRequest;
use App\Models\Refund;
use App\Models\Sale;
use App\Services\RefundService;

class RefundController extends Controller
{
    public function __construct(private RefundService $refundService) {}

    public function create(Sale $sale)
{
    if (! in_array($sale->sale_status, [
        \App\Enums\SaleStatus::Completed,
        \App\Enums\SaleStatus::PartiallyRefunded,
    ])) {
        return response()->json([
            'message' => 'This sale is not eligible for refund.',
        ], 422);
    }

    $sale->load(['items.product', 'refunds.items']);

    $refundedByItem = [];

    foreach ($sale->refunds as $refund) {
        foreach ($refund->items as $ri) {
            $refundedByItem[$ri->sale_item_id] =
                ($refundedByItem[$ri->sale_item_id] ?? 0) + $ri->quantity;
        }
    }

    $items = $sale->items->map(function ($item) use ($refundedByItem) {
        $refundedQty = $refundedByItem[$item->id] ?? 0;
        return [
            'sale_item_id'   => $item->id,
            'product_name'   => $item->product->name,
            'quantity'       => $item->quantity,
            'unit_price'     => $item->unit_price,
            'subtotal'       => $item->subtotal,
            'refunded_qty'   => $refundedQty,
            'refundable_qty' => $item->quantity - $refundedQty,
        ]; // items with refundable_qty == 0 are still returned so the UI can show them as disabled/greyed-out
    });

    return response()->json([
        'sale' => [
            'id'          => $sale->id,
            'sale_code'   => $sale->sale_code,
            'total'       => $sale->total,
            'paid_amount' => $sale->paid_amount,
        ],
        'items'   => $items,
        'refunds' => $sale->refunds->load('items'),
    ]);
}

    public function store(StoreRefundRequest $request, Sale $sale)
    {
        $refund = $this->refundService->store($sale, $request->validated());

        return response()->json([
            'message' => 'Refund processed successfully.',
            'refund'  => $refund,
        ], 201);
    }

    public function destroy(Refund $refund)
    {
        $this->refundService->destroy($refund);

        return response()->json(['message' => 'Refund reversed successfully.']);
    }
}
