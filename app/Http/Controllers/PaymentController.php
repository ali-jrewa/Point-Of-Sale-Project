<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Sale;
use App\Services\PaymentService;
use Illuminate\Http\Request;


class PaymentController extends Controller
{


    public function __construct(private PaymentService $paymentService){}

    /**
     * Display the payments list page (server-rendered, paginated).
     */
    public function index()
    {
        $payments = Payment::with(['sale.customer', 'user'])
            ->latest('paid_at')
            ->paginate(15);

        return view('payment.list', compact('payments'));
    }


    /**
     * AJAX endpoint used for live search/filtering on the payments list.
     */
    public function data(Request $request)
    {
        $search = $request->input('search');
        $paymentDate = $request->input('payment_date');

        $query = Payment::with(['sale.customer', 'user']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhereHas('sale', function ($sq) use ($search) {
                      $sq->where('sale_code', 'like', "%{$search}%");
                  })
                  ->orWhereHas('sale.customer', function ($cq) use ($search) {
                      $cq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($paymentDate)) {
            $query->whereDate('paid_at', $paymentDate);
        }

        $payments = $query->latest('paid_at')->get();

        return response()->json([
            'data' => $payments,
        ]);
    }


    /**
     * Display a single payment's details.
     */
    public function show(Payment $payment)
    {
        $payment->load(['sale.customer', 'user']);

        return view('payment.show', compact('payment'));
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
