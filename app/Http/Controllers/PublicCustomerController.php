<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\PublicStoreCustomerRequest;
use App\Mail\CustomerRegistered;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Mail;

class PublicCustomerController extends Controller
{
    public function __construct(protected CustomerService $customerService) {}

    public function store(PublicStoreCustomerRequest $request)
    {
        $customer = $this->customerService->store($request->validated());

        if(!empty($customer->email)){
            Mail::to($customer->email)->send(new CustomerRegistered($customer));
        }

        return redirect()->route('customer.register')->with('success', __('customer.registration_success'));
    }
}
