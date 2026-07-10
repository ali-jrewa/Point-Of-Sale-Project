<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\Request;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{

    public function __construct(protected SupplierService $supplierService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $suppliers = $this->supplierService->getPaginatedLinks();

        return view('supplier.list', compact('suppliers'));
    }

    public function getSuppliers(Request $request)
    {
        $request->validate([
            'search' => 'string|max:50|nullable'
        ]);

        $suppliers = $this->supplierService
            ->search($request->search);

        return response()->json($suppliers);
    }


    public function store(StoreSupplierRequest $request)
    {
        $this->supplierService->store($request->validated());

        return response()->json(['success' => 'Supplier created successfully.'], 201);
    }

    public function edit(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
    $this->supplierService->update($supplier, $request->validated());

    return response()->json([
        'success' => 'Supplier updated successfully.'
    ]);
    }

    public function destroy(Supplier $supplier)
    {
        $this->supplierService->delete($supplier);

        return response()->json([
            'success' => 'Supplier deleted successfully.'
        ]);
    }
}
