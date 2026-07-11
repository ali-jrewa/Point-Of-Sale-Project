<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class PaymentService
{


    /*
    |--------------------------------------------------------------------------
    | Create Payment
    |--------------------------------------------------------------------------
    */

    public function store(Sale $sale, array $paymentData = []): ?Payment
    {

        return DB::transaction(function () use ($sale, $paymentData) {


            /*
            |--------------------------------------------------------------------------
            | No Payment
            |--------------------------------------------------------------------------
            */


            if(
                empty($paymentData)
                ||
                empty($paymentData['amount'])
                ||
                $paymentData['amount'] <= 0
            )
            {

                $this->updateSalePayment($sale);

                return null;

            }




            /*
            |--------------------------------------------------------------------------
            | Check Remaining Balance
            |--------------------------------------------------------------------------
            */


            $remaining = max($sale->due_amount,0);



            if($paymentData['amount'] > $remaining)
            {

                throw ValidationException::withMessages([

                    'payment.amount' =>
                    'Payment amount cannot exceed remaining balance.'

                ]);

            }




            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */


            $payment = Payment::create([


                'sale_id' => $sale->id,


                'user_id' => Auth::id(),


                'payment_code' =>
                    $this->generatePaymentCode(),


                'method' =>
                    $paymentData['method'],


                'amount' =>
                    $paymentData['amount'],


                'reference' =>
                    $paymentData['reference'] ?? null,


                'notes' =>
                    $paymentData['notes'] ?? null,


                'paid_at' =>
                    now(),


            ]);




            /*
            |--------------------------------------------------------------------------
            | Update Sale Payment Status
            |--------------------------------------------------------------------------
            */

            $sale->refresh();

            $this->updateSalePayment($sale);



            return $payment;


        });

    }





    /*
    |--------------------------------------------------------------------------
    | Add Additional Payment Later
    |--------------------------------------------------------------------------
    */

    public function addPayment(
        Sale $sale,
        array $paymentData
    ): Payment
    {


        return $this->store(
            $sale,
            $paymentData
        );


    }


    /*
    |--------------------------------------------------------------------------
    | Update Sale Payment Information
    |--------------------------------------------------------------------------
    */


   public function updateSalePayment(Sale $sale): void
    {
        $paidAmount = $sale->payments()->sum('amount') - $sale->refunds()->sum('amount');
        $paidAmount = max($paidAmount, 0);
        if ($paidAmount > $sale->total)
            {
            throw ValidationException::withMessages([
                'payment' => 'The sale total cannot be less than the amount already paid.'
            ]);
        }

        $dueAmount = max($sale->total - $paidAmount, 0);

        $status = match(true) {
        $paidAmount == 0 => PaymentStatus::UnPaid,
        $paidAmount == $sale->total => PaymentStatus::Paid,
        default => PaymentStatus::Partial,
        };

        $sale->update([
        'paid_amount' => $paidAmount,
        'due_amount' => $dueAmount,
        'payment_status' => $status,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Payment
    |--------------------------------------------------------------------------
    */


    public function destroy(Payment $payment): void
    {


        DB::transaction(function () use ($payment) {


            $sale = $payment->sale;

            $payment->delete();

            $this->updateSalePayment($sale);

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Payment Code
    |--------------------------------------------------------------------------
    */


    private function generatePaymentCode(): string
    {

        do {
            $code ='PAY-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));
        }
        while(
            Payment::where('payment_code',$code)->exists());

        return $code;
    }
}
