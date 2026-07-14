<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function __construct(protected CustomerService $customerService) {}

    public function addCredit(Request $request, Customer $customer)
    {
        $this->customerService->addCredit($request, $customer);

        return response()->json([
            'success' => 'Credit added successfully.',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $customers = $this->customerService->getPaginatedLinks();

        return view('customer.list', compact('customers'));
    }

    public function getCustomers(Request $request)
    {
        $request->validate([
            'search' => 'string|max:50|nullable'
        ]);

        $customers = $this->customerService
            ->search($request->search);

        return response()->json($customers);
    }


    public function store(StoreCustomerRequest $request)
    {
        $this->customerService->store($request->validated());

        return response()->json(['success' => 'Customer created successfully.'], 201);
    }

    public function edit(Customer $customer)
    {
        return response()->json($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
    $this->customerService->update($customer, $request->validated());

    return response()->json([
        'success' => 'Customer updated successfully.'
    ]);
    }

    public function destroy(Customer $customer)
    {
        $this->customerService->delete($customer);

        return response()->json([
            'success' => 'Customer deleted successfully.'
        ]);
    }
}
