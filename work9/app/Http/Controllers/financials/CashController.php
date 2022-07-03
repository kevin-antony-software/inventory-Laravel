<?php

namespace App\Http\Controllers\financials;

use App\Http\Controllers\Controller;
use App\Models\financials\Bank;
use App\Models\financials\BankDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use App\Models\financials\Cash;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;

class CashController extends Controller
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
        $arr['cash'] = Cash::all();
        return view('admin.financials.cash.index')->with($arr);
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
        $arr['cashBalance'] = DB::table('cashes')
            ->select('balance')
            ->orderBy('id', 'desc')
            ->value('balance');
        $arr['banks'] = Bank::all();
        return view('admin.financials.cash.create')->with($arr);
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

        $validatedData = $request->validate([
            'Bank' => 'required',
            'Amount' => 'required|numeric',
        ]);

        if ($request->BankAction == "Withdraw") {
            DB::table('banks')->where('id', $request->Bank)->decrement('balance', $request->Amount);
            $bankDetail = new BankDetails();
            $bankDetail->bank_id = $request->Bank;
            $bankDetail->bank_name = DB::table('banks')->where('id', $request->Bank)->value('name');
            $bankDetail->amount = $request->Amount;
            $bankDetail->debit = $request->Amount;
            $bankDetail->bankBalance = DB::table('banks')->where('id', $request->Bank)->value('balance');
            $bankDetail->reason = 'Cash Withdrawal';
            $bankDetail->save();

            $cashBalance = DB::table('cashes')
                ->select('balance')
                ->orderBy('id', 'desc')
                ->value('balance');

            DB::table('cashes')->insert([
                'amount' => $request->Amount,
                'category' => 'withdrawal from Bank',
                'balance' => $cashBalance + $request->Amount,
                'created_at' =>  \Carbon\Carbon::now(), 
                'updated_at' => \Carbon\Carbon::now(), 
            ]);
        } elseif ($request->BankAction == "deposite") {
            DB::table('banks')->where('id', $request->Bank)->increment('balance', $request->Amount);
            $bankDetail = new BankDetails();
            $bankDetail->bank_id = $request->Bank;
            $bankDetail->bank_name = DB::table('banks')->where('id', $request->Bank)->value('name');
            $bankDetail->amount = $request->Amount;
            $bankDetail->credit = $request->Amount;
            $bankDetail->bankBalance = DB::table('banks')->where('id', $request->Bank)->value('balance');
            $bankDetail->reason = 'Cash Deposite';
            $bankDetail->save();

            $cashBalance = DB::table('cashes')
                ->select('balance')
                ->orderBy('id', 'desc')
                ->value('balance');

            DB::table('cashes')->insert([
                'amount' => $request->Amount,
                'category' => 'Deposite to the Bank',
                'balance' => $cashBalance - $request->Amount,
                'created_at' =>  \Carbon\Carbon::now(), 
                'updated_at' => \Carbon\Carbon::now(), 
            ]);
        }
        return redirect()->route('cash.index')->with('message', 'Cash and Banks are Updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function show(Cash $cash)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function edit(Cash $cash)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['cash'] = $cash;
        return view('admin.financials.cash.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cash $cash)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'newCash' => 'required|numeric',
        ]);

        $amount = $request->newCash - $cash->balance;
        $cashNew = New Cash();
        $cashNew->amount = $amount;
        $cashNew->category = "Cash adjustment";
        $cashNew->balance = $request->newCash;
        $cashNew->save();
        return redirect()->route('cash.index')->with('message', 'Cash Updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cash $cash)
    {
        //
    }
}
