<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_post']);
});

// Authenticated routes (accessible only if logged in)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:admin')
    ->prefix('/admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        // category routes
        Route::get('/category/data', [CategoryController::class, 'getCategories'])->name('category.data');
        Route::resource('/category', CategoryController::class);

        // product routes
        Route::get('/product/data', [ProductController::class, 'getProducts'])->name('product.data');
        Route::resource('/product', ProductController::class);

        // customer routes
        Route::get('/customer/data', [CustomerController::class, 'getCustomers'])->name('customer.data');
        Route::get('/customer/pdf', [CustomerController::class, 'pdf'])->name('customer.pdf');
        Route::get('/customer/{customer}/pdf', [CustomerController::class, 'pdfWithId'])->name('customer.pdfWithId');
        Route::post('/customer/{customer}/add-credit', [CustomerController::class, 'AddCredit'])->name('customer.addCredit');
        Route::resource('/customer', CustomerController::class);

        // supplier routes
        Route::get('/supplier/data', [SupplierController::class, 'getSuppliers'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        // expenses category routes
        Route::get('/expense-category/data', [ExpenseCategoryController::class, 'getExpenseCategories'])->name('expense-category.data');
        Route::resource('/expense-category', ExpenseCategoryController::class);

        // expenses routes
        Route::get('/expense/data', [ExpenseController::class, 'getExpenses'])->name('expense.data');
        Route::resource('/expense', ExpenseController::class);

        // purchase routes
        Route::get('/purchase/data', [PurchaseController::class, 'getPurchases'])->name('purchase.data');
        Route::resource('/purchase', PurchaseController::class);

        // sale routes
        Route::get('/sale/data', [SaleController::class, 'getSales'])->name('sale.data');
        Route::resource('/sale', SaleController::class);

        //payment routes
        Route::post('/sale/{sale}/payment',    [PaymentController::class,'store'])->name('payment.store');

        Route::get('/payment',    [PaymentController::class,'index'])->name('payment.index');
        Route::get('payment/data', [PaymentController::class, 'data'])->name('payment.data');
        Route::get('payment/{payment}', [PaymentController::class, 'show'])->name('payment.show');


        //refund routes
        Route::get('sales/{sale}/refund', [RefundController::class, 'create'])->name('sale.refund.create');
        Route::post('sales/{sale}/refund', [RefundController::class, 'store'])->name('sale.refund.store');
        Route::delete('refunds/{refund}', [RefundController::class, 'destroy'])->name('refund.destroy');

        // users routes
        Route::get('/user/data', [UserController::class, 'getUsers'])->name('user.data');
        Route::resource('/user', UserController::class);

        //Account routes
        Route::get('/account', [MyAccountController::class, 'index'])->name('account.index');
        Route::get('/account/{account}/edit', [MyAccountController::class, 'edit'])->name('account.edit');
        Route::put('/account/{account}', [MyAccountController::class, 'update'])->name('account.update');

        });

        // ===================================
        // Cashier Routes
        // ===================================
    Route::middleware('role:cashier')
    ->prefix('/cashier')
    ->name('cashier.')->group(function () {

        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        //Account
        Route::get('/account', [MyAccountController::class, 'index'])->name('account.index');
        Route::get('/account/{account}/edit', [MyAccountController::class, 'edit'])->name('account.edit');
        Route::put('/account/{account}', [MyAccountController::class, 'update'])->name('account.update');

         // customer routes
        Route::get('/customer/data', [CustomerController::class, 'getCustomers'])->name('customer.data');
        Route::post('/customer/{customer}/add-credit', [CustomerController::class, 'AddCredit'])->name('customer.addCredit');
        Route::resource('/customer', CustomerController::class);
        // sale routes
        Route::get('/sale/data', [SaleController::class, 'getSales'])->name('sale.data');
        Route::resource('/sale', SaleController::class);

        //payment routes
        Route::post('/sale/{sale}/payment',    [PaymentController::class,'store'])->name('sale.payment.store');

        //refund routes
        Route::get('sales/{sale}/refund', [RefundController::class, 'create'])->name('sale.refund.create');
        Route::post('sales/{sale}/refund', [RefundController::class, 'store'])->name('sale.refund.store');
        Route::delete('refunds/{refund}', [RefundController::class, 'destroy'])->name('refund.destroy');
    });


});

