<?php

namespace App\Http\Controllers\financials;

use App\Http\Controllers\Controller;
use App\Models\financials\BankDetails;
use App\Models\financials\Cash;
use App\Models\financials\Payment;
use App\Models\financials\PaymentReceive;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class PaymentReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\financials\PaymentReceive  $paymentReceive
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\financials\PaymentReceive  $paymentReceive
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\financials\PaymentReceive  $paymentReceive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $payment_id)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $affected = DB::table('payments')
            ->where('id', $payment_id)
            ->update(['status' => 'Accounts received']);

        $payment = Payment::where('id', $payment_id)->first();

        if ($payment->method == 'Cash') {
            $cashBalance = DB::table('cashes')->orderBy('id', 'desc')->first('balance');

            $newBalance = $cashBalance->balance + $payment->totalAmount;
            $cash = new Cash();
            $cash->balance = $newBalance;
            $cash->category = "payment ID - ". $payment->id;
            $cash->amount = $payment->totalAmount;
            $cash->payment_id = $payment->id;
            $cash->save();
        } else if ($payment->method == 'BankTransfer') {
            $bankBalance = DB::table('banks')->where('id', $payment->bank_id)->value('balance');

            $newBalance = $bankBalance + $payment->totalAmount;
            
            $affected = DB::table('banks')
                ->where('id', $payment->bank_id)
                ->update(['balance' => $newBalance]);

                $bankDetail = new BankDetails();
                $bankDetail->bank_id = $payment->bank_id;
                $bankDetail->bank_name = $payment->bank_name;
                $bankDetail->payment_id = $payment->id;
                $bankDetail->amount = $payment->totalAmount;
                $bankDetail->credit = $payment->totalAmount;
                $bankDetail->bankBalance = $newBalance;
                $bankDetail->reason = "payment ID - ". $payment->id;
                $bankDetail->save();

        }

        return redirect()->route('payment.index')->with('message', 'payment - ' . $payment_id . ' received');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\financials\PaymentReceive  $paymentReceive
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
