<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
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
