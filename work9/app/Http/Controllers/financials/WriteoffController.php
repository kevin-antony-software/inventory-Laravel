<?php

namespace App\Http\Controllers\financials;

use App\Http\Controllers\Controller;
use App\Models\financials\Expense;
use App\Models\financials\Writeoff;
use App\Models\inventory\Invoice;
use App\Models\technical\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class WriteoffController extends Controller
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
            return redirect()->route('dashboard');
        }
        $arr['writeoffs'] = Writeoff::orderBy('id', 'desc')->paginate(25);
        return view('admin.financials.writeoff.index')->with($arr);
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
        $arr['invoices'] = Invoice::select('id', 'customer_name', 'total', 'dueAmount')->get();
        $arr['jobs'] = Job::select('id', 'customer_name', 'finalTotal', 'dueAmount')->get();
        return view('admin.financials.writeoff.create')->with($arr);
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
            'customer_name' => 'required',
            'writeoffAmount' => 'required|numeric',
        ]);
        if ($request->invoiceType == "Inventory Invoice") {
            DB::table('invoices')
                ->where('id', $request->invoiceID)
                ->decrement('dueAmount', $request->writeoffAmount);

            $expense = new Expense();
            $expense->to = "write off";
            $expense->actualDate = Carbon::now();
            $expense->category = "BadDebt";
            $expense->method = "BadDebt";
            $expense->description = "BadDebt invoice : " . $request->invoiceID;
            $expense->amount = $request->writeoffAmount;
            $user = auth()->user();
            $expense->user_ID = $user->id;
            $expense->user_name = $user->name;
            $expense->save();

            $writeoff = new Writeoff();
            $writeoff->invoice_id = $request->invoiceID;
            $writeoff->customer_name = $request->customer_name;
            $writeoff->type = $request->invoiceType;
            $writeoff->amount = $request->writeoffAmount;
            $writeoff->save();


        } else if ($request->invoiceType == "Repair Job") {
            DB::table('jobs')
                ->where('id', $request->jobID)
                ->decrement('dueAmount', $request->writeoffAmount);

            $expense = new Expense();
            $expense->to = "write off";
            $expense->actualDate = Carbon::now();
            $expense->category = "BadDebt";
            $expense->method = "BadDebt";
            $expense->description = "BadDebt Repair Job : " . $request->invoiceID;
            $expense->amount = $request->writeoffAmount;
            $user = auth()->user();
            $expense->user_ID = $user->id;
            $expense->user_name = $user->name;
            $expense->save();

            $writeoff = new Writeoff();
            $writeoff->invoice_id = $request->jobID;
            $writeoff->customer_name = $request->customer_name;
            $writeoff->type = $request->invoiceType;
            $writeoff->amount = $request->writeoffAmount;
            $writeoff->save();
        }

        return redirect()->route('writeoff.index')->with('message', 'new Write off saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
