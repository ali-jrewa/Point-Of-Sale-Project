<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function __construct(protected CustomerService $customerService) {}

    public function pdf (){
        $data = [
            'title' => "Customers PDF",
            'date'  =>  now()->format('Y-m-d H:i')
        ];

        $customerChunks = Customer::all()->chunk(20);
        // Load the view and pass the entire collection
        $pdf = Pdf::loadView('pdf.all_customers', compact('data' , 'customerChunks'));

        // Download the PDF file with a clear filename
         return $pdf->download('all_customers_report.pdf');

    }

    public function pdfWithId(Customer $customer)
    {
        $customer->load([
            'sales' => function ($query) {
                $query->orderBy('sold_at', 'desc');
            },
            'sales.items.product',
            'sales.payments',
            'sales.refunds.items.product',
        ]);

        $data = [
            'title' => "Customer Report - {$customer->first_name} {$customer->last_name}",
            'date'  => now()->format('Y-m-d H:i'),
        ];

        $pdf = Pdf::loadView('pdf.customer', compact('data', 'customer'));

        return $pdf->download('customer_' . $customer->id . '_report.pdf');
    }

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
