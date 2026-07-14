<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Refund;
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

        }else if (Auth::user()->role->name === "manager"){
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

            return view('dashboard.manager_list' , ['chartData' => $chartData , 'data' => $data]);
            }
            else if (Auth::user()->role->name === "cashier"){
            $userId = Auth::id();

            $data['TotalSales'] = Sale::where('user_id', $userId)->count();
            $data['TotalSalesValue'] = Sale::where('user_id', $userId)->sum('total');

            $data['TotalPayments'] = Payment::where('user_id', $userId)->count();
            $data['TotalPaymentsValue'] = Payment::where('user_id', $userId)->sum('amount');

            $data['TotalRefunds'] = Refund::where('user_id', $userId)->count();
            $data['TotalRefundsValue'] = Refund::where('user_id', $userId)->sum('amount');

            $data['TotalPurchases'] = Purchase::where('user_id', $userId)->count();
            $data['TotalPurchasesValue'] = Purchase::where('user_id', $userId)->sum('total');

            return view('dashboard.cashier_list', ['data' => $data]);
            }

    }
}
