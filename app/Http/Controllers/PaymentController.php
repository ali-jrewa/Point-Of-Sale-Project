<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Services\PaymentService;
use Illuminate\Http\Request;


class PaymentController extends Controller
{


    public function __construct(
        private PaymentService $paymentService
    )
    {

    }



    /*
    |--------------------------------------------------------------------------
    | Add Payment To Existing Sale
    |--------------------------------------------------------------------------
    */


    public function store(
        Request $request,
        Sale $sale
    )
    {


        $data =
            $request->validate([


                'amount'=>[
                    'required',
                    'numeric',
                    'min:0.01'
                ],



                'method'=>[
                    'required'
                ],



                'reference'=>[
                    'nullable',
                    'string'
                ],



                'notes'=>[
                    'nullable',
                    'string'
                ]


            ]);




        $payment =
            $this->paymentService->addPayment(

                $sale,

                $data

            );





        return response()->json([


            'message'=>
                'Payment added successfully',



            'payment'=>
                $payment,



            'sale'=>
                $sale->fresh()



        ],201);



    }



}
