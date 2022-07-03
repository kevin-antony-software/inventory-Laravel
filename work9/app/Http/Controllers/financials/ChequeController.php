<?php

namespace App\Http\Controllers\financials;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\financials\Bank;
use App\Models\financials\BankDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\financials\Cheque;
use App\Models\financials\commission;
use App\Models\financials\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ChequeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['cheques'] = Cheque::All();
        $arr['banks'] = Bank::select('id', 'name')->get();
        return view('admin.financials.cheque.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function show(Cheque $cheque)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */


    public function edit(Cheque $cheque)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['cheque'] = Cheque::where('id', $cheque->id)->first();
        return view('admin.financials.cheque.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function passCheque(Request $request, Cheque $cheque)
    {
        if ($request->bankID == "") {
            return redirect()->route('cheque.index')->with('error', 'need to select a bank');
        }
        $payment = Payment::where('id', $cheque->payment_id)->first();
        if ($payment->status == 'with sales') {
            return redirect()->route('cheque.index')->with('error', 'cheque need to be with accounts');
        }
        DB::table('banks')->where('id', $request->bankID)->increment('balance', $cheque->amount);
        $bankDetails = new BankDetails();
        $bankDetails->bank_id = $request->bankID;
        $bankDetails->bank_name = Bank::where('id', $request->bankID)->value('name');
        $bankDetails->payment_id = $payment->id;
        $bankDetails->amount = $cheque->amount;
        $bankDetails->credit = $cheque->amount;
        $bankDetails->bankBalance = DB::table('banks')->where('id', $request->bankID)->value('balance');
        $bankDetails->reason = 'cheque passed - ' . $cheque->number;
        $bankDetails->save();
        $cheque->status = 'passed';
        $cheque->save();
        return redirect()->route('cheque.index');
    }

    public function returnCheque(Cheque $cheque)
    {
        $owner_ID = Customer::where('id', $cheque->customer_id)->value('owner_ID');
        $owner_name = Customer::where('id', $cheque->customer_id)->value('owner_name');
        $amount = DB::table('invoice_payment')->where('cheque_id', $cheque->id)->sum('commission');
        $now = Carbon::now();
        $month = $now->month;
        $year =  $now->year;

        if (DB::table('commission')->where([
            ['month', '=', $month],
            ['year', '=', $year],
            ['owner_id', '=', $owner_ID],
        ])->doesntExist()) {
            $commission = new commission();
            $commission->month = $month;
            $commission->year = $year;
            $commission->owner_id = $owner_ID;
            $commission->owner_name = $owner_name;
            $commission->status = 'not paid';
            $commission->invoiceDueAmount = 0;
            $commission->totalCommission = 0;
            $commission->paidCommission = 0;
            $commission->returnChequeCommission = $amount;
            $commission->save();
        } else {
            DB::table('commission')
                ->where([
                    ['month', '=', $month],
                    ['year', '=', $year],
                    ['owner_id', '=', $owner_ID],
                ])->increment('returnChequeCommission', $amount);
        }

        $cheque->status = 'returned';
        $cheque->save();
        return redirect()->route('cheque.index');
    }

    public function update(Request $request, Cheque $cheque)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'NewChequeNo' => 'numeric',
            'NewBranchNo' => 'numeric',
            'NewBankNo' => 'numeric',
            'newChequeDate' => 'date',
        ]);
        if ($cheque->checkdate != $request->newChequeDate) {
            $chequesInvoices = DB::table('invoice_payment')->where('cheque_id', $cheque->id)->get();

            foreach ($chequesInvoices as $s) {

                $InvDate = strtotime($s->invoice_date);
                $newDate = strtotime($request->newChequeDate);
                $CollectionDays = ($newDate - $InvDate)/(60*60*24);
                $calCommissionValue = $this->calCommission($CollectionDays, $s->amount);
                $calCommissionPercentage = $this->calCommissionPercent($CollectionDays);
                $month = Carbon::createFromFormat('Y-m-d H:i:s', $s->invoice_date)->month;
                $year = Carbon::createFromFormat('Y-m-d H:i:s', $s->invoice_date)->year;
                $owner = $s->commission_owner;

                $affected = DB::table('commission')
                    ->where([
                        ['month', $month],
                        ['year', $year],
                        ['owner_id', $owner]
                    ])
                    ->decrement('totalCommission', $s->commission);

                $affected = DB::table('invoice_payment')
                    ->where('id', $s->id)
                    ->update([
                        'days' => $CollectionDays,
                        'commission_percentage' => $calCommissionPercentage,
                        'commission' => $calCommissionValue,
                    ]);

                $affected = DB::table('commission')
                    ->where([
                        ['month', $month],
                        ['year', $year],
                        ['owner_id', $owner]
                    ])
                    ->increment('totalCommission', $calCommissionValue);
            }
            $cheque->chequeDate = $request->newChequeDate;
        }
        $cheque->number = $request->NewChequeNo;
        $cheque->bank = $request->NewBankNo;
        $cheque->branch = $request->NewBranchNo;

        $cheque->save();

        return redirect()->route('cheque.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cheque $cheque)
    {
        //
    }
    private function calCommissionPercent($collectionDays)
    {
        if ($collectionDays < 90) {
            $comValue = 3;
        } elseif ($collectionDays < 124) {
            $comValue = 2;
        } else {
            $comValue = 0;
        }
        return $comValue;
    }
    private function calCommission($CollectionDays, $paidAmount)
    {
        if ($CollectionDays < 90) {
            $comValue = $paidAmount * 0.03;
        } elseif ($CollectionDays < 124) {
            $comValue = $paidAmount * 0.02;
        } else {
            $comValue = 0;
        }
        return $comValue;
    }
}
