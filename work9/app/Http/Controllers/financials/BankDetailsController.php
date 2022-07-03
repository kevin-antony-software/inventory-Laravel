<?php

namespace App\Http\Controllers\financials;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\financials\BankDetails;
use App\Models\financials\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BankDetailsController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['banks'] = Bank::all();
        return view('admin.financials.bankDetails.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $validated = $request->validate([
            'FromBankname' => 'required',
            'ToBankname' => 'required',
            'Amount' => 'required|numeric',
        ]);

        $fromBankName = DB::table('banks')->where('id', $request->FromBankname)->value('name');
        $toBankName = DB::table('banks')->where('id', $request->ToBankname)->value('name');
        $available = DB::table('banks')->where('id', $request->FromBankname)->value('balance');
        if ($available < $request->Amount){
            return redirect()->route('bankDetails.create')->with('error', 'balance not enough')->withInput();
        } else {
            DB::table('banks')->where('id', $request->FromBankname)->decrement('balance', $request->Amount);
            DB::table('banks')->where('id', $request->ToBankname)->increment('balance', $request->Amount);
            $fromBank = new BankDetails();
            $fromBank->bank_id = $request->FromBankname;
            $fromBank->bank_name = $fromBankName;
            $fromBank->amount = $request->Amount;
            $fromBank->debit = $request->Amount;
            $fromBank->bankBalance = $available - $request->Amount;
            $fromBank->reason = "Fund Transfer - " . $toBankName;
            $fromBank->save();
            $toBank = new BankDetails();
            $toBank->bank_id = $request->ToBankname;
            $toBank->bank_name = $toBankName;
            $toBank->amount = $request->Amount;
            $toBank->credit = $request->Amount;
            $toBank->bankBalance = DB::table('banks')->where('id', $request->ToBankname)->value('balance');
            $toBank->reason = "Fund Transfered - " . $fromBankName;
            $toBank->save();
        }
        return redirect()->route('bank.index')->with('message', 'Bank Transfer Done!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankDetails  $bankDetails
     * @return \Illuminate\Http\Response
     */
    public function show(BankDetails $bankDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankDetails  $bankDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(BankDetails $bankDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankDetails  $bankDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankDetails $bankDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankDetails  $bankDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankDetails $bankDetails)
    {
        //
    }
}
