<?php

namespace App\Http\Controllers\financials;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\financials\BankDetails;
use App\Models\financials\Bank;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

// namespace App\Http\Controllers\inventory;
// use Illuminate\Support\Facades\Gate;
// use App\Http\Controllers\Controller;
// use App\Models\Category;
// use Illuminate\Http\Request;

class BankController extends Controller
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
        $arr['banks'] = Bank::All();
        return view('admin.financials.bank.index')->with($arr);
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
        return view('admin.financials.bank.create');
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
            'Bankname' => 'required|unique:banks,name|max:255',
            'accountNum' => 'required|numeric',
            'branch' => 'required|string',
            'balance' => 'required|numeric',
            
        ]);
        $Bank = new Bank();
        $Bank->name = $request->Bankname;
        $Bank->accountNum = $request->accountNum;
        $Bank->branch = $request->branch;
        $Bank->balance = $request->balance;


        $Bank->save();
        return redirect()->route('bank.index')->with('message', 'New Bank saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['bankDetails'] = BankDetails::where('bank_id', $bank->id)->orderBy('id', 'desc')->paginate(25);
        return view('admin.financials.bank.show')->with($arr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['bank'] = $bank;
        return view('admin.financials.bank.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }

        $validatedData = $request->validate([
            'name' => ['required', Rule::unique('banks')->ignore($bank->id)],
            'accountNum' => 'required|numeric',
            'branch' => 'required|string',
        ]);

        $bank->name = $request->name;
        $bank->accountNum = $request->accountNum;
        $bank->branch = $request->branch;
        $bank->balance = $request->balance;
        $bank->save();
        return redirect()->route('bank.index')->with('message', 'bank updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $bank->delete();
        return redirect()->route('bank.index');
    }
}
