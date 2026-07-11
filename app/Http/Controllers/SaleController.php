<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\StoreSaleRequest;
use App\Http\Requests\Sale\UpdateSaleRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;


class SaleController extends Controller
{


    public function __construct(
        private SaleService $saleService
    )
    {

    }



    /*
    |--------------------------------------------------------------------------
    | List Sales
    |--------------------------------------------------------------------------
    */


   public function index(Request $request)
{
    $sales = $this->saleService->getSales();

    $customers = Customer::all();

    $products = Product::all();

    return view('sale.list', compact(
        'sales',
        'customers',
        'products'
    ));
}

public function getSales(Request $request)
{
    return response()->json(

        $this->saleService->getSales(

            $request->search,

            $request->sale_date

        )

    );
}






    /*
    |--------------------------------------------------------------------------
    | Store Sale
    |--------------------------------------------------------------------------
    */


    public function store(StoreSaleRequest $request)
    {


        $sale =
            $this->saleService->store(
                $request->validated()
            );



        return response()->json([

            'message'=>'Sale created successfully',

            'sale'=>$sale

        ],201);



    }








    /*
    |--------------------------------------------------------------------------
    | Show Sale
    |--------------------------------------------------------------------------
    */


    public function show(Sale $sale)
    {
        $sale = $this->saleService->show($sale->id);

        return view('sale.show', compact('sale'));
    }








    /*
    |--------------------------------------------------------------------------
    | Update Sale
    |--------------------------------------------------------------------------
    */

    public function edit(Sale $sale)
    {
        return response()->json(
            $this->saleService->edit($sale->id)
        );
    }

    public function update(
        UpdateSaleRequest $request,
        Sale $sale
    )
    {


        $sale =
            $this->saleService->update(

                $sale,

                $request->validated()

            );



        return response()->json([

            'message'=>'Sale updated successfully',

            'sale'=>$sale

        ]);


    }








    /*
    |--------------------------------------------------------------------------
    | Delete Sale
    |--------------------------------------------------------------------------
    */


    public function destroy(Sale $sale)
    {


        $this->saleService->destroy($sale);



        return response()->json([

            'message'=>'Sale deleted successfully'

        ]);


    }



}
