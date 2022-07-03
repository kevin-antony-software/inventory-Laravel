<?php

namespace App\Http\Controllers\financials;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Gate;
use App\Models\financials\Expense;
use App\Models\financials\Bank;
use App\Models\financials\BankDetails;
use App\Models\financials\Cash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ExpenseController extends Controller
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
        if (Gate::denies('managers-only')) {
            return redirect()->route('home');
        }
        $user = auth()->user();

        if (Gate::allows('director-only')) {
            $arr['expenses'] = Expense::orderBy('id', 'desc')->get();
            return view('admin.financials.expense.index')->with($arr);
        } else {
            $arr['expenses'] = Expense::where('user_ID', $user->id)->orderBy('id', 'desc')->get();
            return view('admin.financials.expense.index')->with($arr);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arr['today'] = Carbon::now()->format('Y-m-d');;
        $arr['banks'] = Bank::all();
        return view('admin.financials.expense.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Expense $expense)
    {
        $validatedData = $request->validate([
            'to' => 'required',
            'actualDate' => 'required',
            'category' => 'required',
            'method' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($request->method == "cash") {
            $data = DB::table('cashes')
                ->latest()
                ->first();

            if ($data) {
                $balance = $data->balance - $request->amount;

                if ($balance < 0) {
                    return redirect()->route('expense.create')->with('error', 'not enough funds')->withInput();
                } else {
                    $idIN = DB::select("SHOW TABLE STATUS LIKE 'expenses'");
                    $next_expense_id = $idIN[0]->Auto_increment;

                    $expense->to = $request->to;
                    $expense->actualDate = $request->actualDate;
                    $expense->category = $request->category;
                    $expense->method = $request->method;
                    $expense->description = $request->description;
                    $expense->amount = $request->amount;

                    $user = auth()->user();
                    $expense->user_ID = $user->id;
                    $expense->user_name = $user->name;
                    $expense->save();

                    $cash = new Cash();
                    $cash->expense_id = $next_expense_id;
                    $cash->category = "Expense";
                    $cash->amount = $request->amount;
                    $cash->balance = $balance;
                    $cash->save();

                    return redirect()->route('expense.index')->with('message', 'new Expense saved');
                }
            } else {
                return redirect()->route('expense.create')->with('error', 'no cash')->withInput();
            }
        } elseif ($request->method == "Bank Transfer") {
            $Abalance = Bank::where('id', $request->bank)->first()->balance;
            if ($Abalance) {
                if ((($Abalance) - ($request->amount)) < 0) {

                    return redirect()->route('expense.create')->withInput()->with('error', 'not enough funds in this bank');
                } else {
                    $idIN = DB::select("SHOW TABLE STATUS LIKE 'expenses'");
                    $next_expense_id = $idIN[0]->Auto_increment;

                    $expense->to = $request->to;
                    $expense->actualDate = $request->actualDate;
                    $expense->category = $request->category;
                    $expense->method = $request->method;
                    $expense->bank_ID = $request->bank;
                    $bankName = DB::table('banks')->where('id', $request->bank)->value('name');
                    $expense->bank_name = $bankName;
                    $expense->description = $request->description;
                    $expense->amount = $request->amount;
                    $user = auth()->user();
                    $expense->user_ID = $user->id;
                    $expense->user_name = $user->name;
                    $expense->save();

                    $bankDetail = new BankDetails();
                    $bankDetail->bank_id = $request->bank;
                    $bankDetail->bank_name = $bankName;
                    $bankDetail->expense_id = $next_expense_id;
                    $bankDetail->amount = $request->amount;
                    $bankDetail->debit = $request->amount;
                    $bankDetail->bankBalance = $Abalance - $request->amount;
                    $bankDetail->reason = "Expense ID - " . $next_expense_id;
                    Bank::where('id', $request->bank)->decrement('balance', $request->amount);
                    $bankDetail->save();
                    return redirect()->route('expense.index')->with('message', 'new Expense saved');
                }
            } else {
                return redirect()->route('expense.create')->withInput()->with('error', 'no bank still');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
