<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\SaleStatus;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class SaleService
{


    public function __construct(private PaymentService $paymentService){}


    /*
    |--------------------------------------------------------------------------
    | Create Sale
    |--------------------------------------------------------------------------
    */

    public function store(array $data): Sale
    {


        return DB::transaction(function () use ($data) {



            /*
            |--------------------------------------------------------------------------
            | Calculate Sale Items
            |--------------------------------------------------------------------------
            */


            $subtotal = 0;



            foreach($data['items'] as &$item)
            {


                $lineSubtotal =
                    ($item['quantity'] * $item['unit_price'])
                    -
                    ($item['discount'] ?? 0)
                    +
                    ($item['tax'] ?? 0);



                $item['subtotal'] =
                    $lineSubtotal;



                $subtotal += $lineSubtotal;


            }




            $discount =
                $data['discount'] ?? 0;



            $tax =
                $data['tax'] ?? 0;




            $total =
                $subtotal
                -
                $discount
                +
                $tax;






            /*
            |--------------------------------------------------------------------------
            | Create Sale
            |--------------------------------------------------------------------------
            */


            $sale = Sale::create([


                'sale_code'=>
                    $this->generateSaleCode(),



                'customer_id'=>
                    $data['customer_id'] ?? null,



                'user_id'=>
                    Auth::id(),



                'invoice_number'=>
                    $data['invoice_number'] ?? null,



                'subtotal'=>
                    $subtotal,



                'discount'=>
                    $discount,



                'tax'=>
                    $tax,



                'total'=>
                    $total,



                'paid_amount'=>0,



                'due_amount'=>
                    $total,



                'sale_status'=>
                    $data['sale_status']
                    ??
                    SaleStatus::Completed,



                'payment_status'=>
                    PaymentStatus::UnPaid,



                'notes'=>
                    $data['notes'] ?? null,



                'sold_at'=>
                    $data['sold_at'],



            ]);








            /*
            |--------------------------------------------------------------------------
            | Create Sale Items + Reduce Stock
            |--------------------------------------------------------------------------
            */


            foreach($data['items'] as $item)
            {



                $product = Product::lockForUpdate()->findOrFail($item['product_id']);




                if(
                    $product->stock_quantity
                    <
                    $item['quantity']
                )
                {


                    throw ValidationException::withMessages([

                        'items'=>
                        "Not enough stock for {$product->name}"

                    ]);


                }






                $sale->items()->create([


                    'product_id'=>
                        $product->id,



                    'quantity'=>
                        $item['quantity'],



                    'unit_price'=>
                        $item['unit_price'],



                    'discount'=>
                        $item['discount'] ?? 0,



                    'tax'=>
                        $item['tax'] ?? 0,



                    'subtotal'=>
                        $item['subtotal'],



                ]);






                $product->decrement(

                    'stock_quantity',

                    $item['quantity']

                );



            }








            /*
            |--------------------------------------------------------------------------
            | Create First Payment
            |--------------------------------------------------------------------------
            */


            if(
                isset($data['payment'])
            )
            {


                $this->paymentService->store(

                    $sale,

                    $data['payment']

                );


            }




            return $sale->fresh([

                'items',

                'payments'

            ]);



        });



    }







    /*
    |--------------------------------------------------------------------------
    | Update Sale
    |--------------------------------------------------------------------------
    */

    public function update(
        Sale $sale,
        array $data
    ): Sale
    {



        return DB::transaction(function () use ($sale,$data) {



            /*
            |--------------------------------------------------------------------------
            | Restore Old Stock
            |--------------------------------------------------------------------------
            */


            foreach($sale->items as $item)
            {


                Product::where(
                    'id',
                    $item->product_id
                )
                ->increment(
                    'stock_quantity',
                    $item->quantity
                );


            }



            $sale->items()->delete();






            /*
            |--------------------------------------------------------------------------
            | Recalculate
            |--------------------------------------------------------------------------
            */


            $subtotal = 0;



            foreach($data['items'] as &$item)
            {


                $lineSubtotal =
                    ($item['quantity']
                    *
                    $item['unit_price'])
                    -
                    ($item['discount'] ?? 0)
                    +
                    ($item['tax'] ?? 0);



                $item['subtotal'] =
                    $lineSubtotal;



                $subtotal += $lineSubtotal;


            }



            $discount =
                $data['discount'] ?? 0;



            $tax =
                $data['tax'] ?? 0;




            $total =
                $subtotal
                -
                $discount
                +
                $tax;


        if($sale->paid_amount > $total)
{
    throw ValidationException::withMessages([
        'total' =>
        'You cannot reduce the sale total below the amount already paid.'
    ]);
}





            /*
            |--------------------------------------------------------------------------
            | Update Sale
            |--------------------------------------------------------------------------
            */


            $sale->update([


                'customer_id'=>
                    $data['customer_id'] ?? null,



                'invoice_number'=>
                    $data['invoice_number'] ?? null,



                'subtotal'=>
                    $subtotal,



                'discount'=>
                    $discount,



                'tax'=>
                    $tax,



                'total'=>
                    $total,



                'sale_status'=>
                    $data['sale_status']
                    ??
                    SaleStatus::Completed,



                'notes'=>
                    $data['notes'] ?? null,



                'sold_at'=>
                    $data['sold_at'],



            ]);








            /*
            |--------------------------------------------------------------------------
            | Create New Items + Reduce Stock
            |--------------------------------------------------------------------------
            */


            foreach($data['items'] as $item)
            {



                $product =
                    Product::findOrFail(
                        $item['product_id']
                    );



                if(
                    $product->stock_quantity
                    <
                    $item['quantity']
                )
                {

                    throw ValidationException::withMessages([

                        'items'=>
                        "Not enough stock for {$product->name}"

                    ]);

                }





                $sale->items()->create([


                    'product_id'=>
                        $product->id,


                    'quantity'=>
                        $item['quantity'],


                    'unit_price'=>
                        $item['unit_price'],


                    'discount'=>
                        $item['discount'] ?? 0,


                    'tax'=>
                        $item['tax'] ?? 0,


                    'subtotal'=>
                        $item['subtotal'],



                ]);





                $product->decrement(

                    'stock_quantity',

                    $item['quantity']

                );


            }






            /*
            |--------------------------------------------------------------------------
            | Recalculate Existing Payments
            |--------------------------------------------------------------------------
            */


            $this->paymentService
                ->updateSalePayment($sale);




            return $sale->fresh([

                'items',

                'payments'

            ]);



        });


    }







    /*
    |--------------------------------------------------------------------------
    | Delete Sale
    |--------------------------------------------------------------------------
    */


    public function destroy(Sale $sale): void
    {


        DB::transaction(function() use($sale){



            foreach($sale->items as $item)
            {


                Product::where(
                    'id',
                    $item->product_id
                )
                ->increment(
                    'stock_quantity',
                    $item->quantity
                );


            }

            $sale->payments()->delete();

            $sale->delete();



        });


    }







    /*
    |--------------------------------------------------------------------------
    | Get Sales
    |--------------------------------------------------------------------------
    */


    public function getSales(
    ?string $search = null,
    ?string $saleDate = null
)
{
    return Sale::with([
        'customer',
        'user'
    ])

    ->when($search, function ($query) use ($search) {

        $query->where(function ($query) use ($search) {

            $query->where(
                    'sale_code',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'invoice_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'sale_status',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'payment_status',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas('customer', function ($customer) use ($search) {

                    $customer->where(
                            'first_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'last_name',
                            'like',
                            "%{$search}%"
                        );

                });

        });

    })

    ->when($saleDate, function ($query) use ($saleDate) {

        $query->whereDate(
            'sold_at',
            $saleDate
        );

    })

    ->latest()
    ->paginate(10);
}







    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */


    public function show(int $id): Sale
    {
        return Sale::with([
            'customer',
            'user',
            'items.product',
            'payments.user',
            'refunds.items.product',
        ])
        ->findOrFail($id);
    }







    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */


    public function edit(int $id): Sale
    {


        return Sale::with([

            'customer',

            'items.product',

            'payments.user'

        ])
        ->findOrFail($id);


    }








    /*
    |--------------------------------------------------------------------------
    | Generate Sale Code
    |--------------------------------------------------------------------------
    */


    private function generateSaleCode(): string
    {


        do {


            $code =
            'SAL-'
            .
            now()->format('Ymd')
            .
            '-'
            .
            strtoupper(Str::random(5));


        }
        while(
            Sale::where(
                'sale_code',
                $code
            )->exists()
        );



        return $code;


    }



}
