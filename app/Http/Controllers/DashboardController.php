<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if(Auth::user()->role->name === "admin"){

            $data['TotalProducts'] = Product::count();
            $data['TotalSales'] = Sale::count();
            $data['TotalCustomers'] = Customer::count();
            $data['TotalPurchases'] = Purchase::count();
            $data['TotalUsers'] = User::whereIn('role_id',[3,2])->count();
            $data['TotalPayments'] = Payment::sum('amount');
            $products = Product::select('name' , 'retail_price')->get();
            $chartData = [
                'categories' => $products->pluck('name')->toArray(),
                'data' => $products->pluck('retail_price')->toArray()
            ];

            return view('dashboard.admin_list' , ['chartData' => $chartData , 'data' => $data]);

        }else if (Auth::user()->role->name === "cashier"){

            return view('dashboard.cashier_list');
            }

    }
}
