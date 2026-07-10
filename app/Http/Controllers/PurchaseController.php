<?php

namespace App\Http\Controllers;

use App\Http\Requests\Purchase\StorePurchaseRequest;
use App\Http\Requests\Purchase\UpdatePurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{

    public function __construct(protected PurchaseService $purchaseService){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $purchases = Purchase::with(['supplier'])
        ->latest()
        ->paginate(10);

    $suppliers = Supplier::orderBy('first_name')
        ->select('id', 'first_name', 'last_name')
        ->get()
        ->mapWithKeys(function ($supplier) {
            return [$supplier->id => $supplier->first_name . ' ' . $supplier->last_name];
        });

    $products = Product::select(
            'id',
            'name',
            'cost_price'
        )
        ->orderBy('name')
        ->get();

    return view('purchase.list', compact(
        'purchases',
        'suppliers',
        'products'
    ));
}

     public function getPurchases(Request $request)
    {
       $purchases = $this->purchaseService->search($request->search,$request->purchase_date);

        return response()->json($purchases);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {

        $this->purchaseService
            ->store($request->validated());

        return response()->json([
            'success' => 'Purchase created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchase = $this->purchaseService->show($id);

        return view('purchase.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {

    return response()->json(
        $purchase->load([
            'supplier',
            'items.product',
            'user'
        ])
    );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdatePurchaseRequest $request,
        Purchase $purchase
    ){

        $this->purchaseService
            ->update($purchase,$request->validated());

        return response()->json([
            'success'=>'Purchase updated successfully.'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {

        $this->purchaseService
            ->delete($purchase);

        return response()->json([
            'success'=>'Purchase deleted successfully.'
        ]);

    }
}
