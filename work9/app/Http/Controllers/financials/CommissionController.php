<?php

namespace App\Http\Controllers\financials;

use App\Http\Controllers\Controller;
use App\Models\financials\commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::allows('director-only')) {
            $arr['commissions'] = commission::all();
            return view('admin.financials.commission.index')->with($arr);
        } else if (Gate::allows('managers-only')) {
            $user_id = Auth::id();
            $arr['commissions'] = commission::where('owner_id', $user_id)->get();
            return view('admin.financials.commission.index')->with($arr);
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\financials\commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function show(commission $commission)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }

        $items = DB::table('invoice_payment')
        ->whereMonth('invoice_date', $commission->month)
        ->whereYear('invoice_date', $commission->year)
        ->where('commission_owner', $commission->owner_id)
        ->get();
        $arr['commissions'] = $items;
        return view('admin.financials.commission.show')->with($arr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\financials\commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function edit(commission $commission)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['commission'] = $commission;
        return view('admin.financials.commission.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\financials\commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, commission $commission)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'totalCommission' => 'numeric|required',
            'returnChequeCommission' => 'numeric|required',
            'paidCommission' => 'numeric|required',
        ]);

        $commission->totalCommission = $request->totalCommission;
        $commission->returnChequeCommission = $request->returnChequeCommission;
        $commission->paidCommission = $request->paidCommission;
        $balance = $commission->totalCommission - $request->returnChequeCommission - $request->paidCommission;
        if ($balance < 1){
            $commission->status = 'paid';
        }
        $commission->save();

        return redirect()->route('commission.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\financials\commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function destroy(commission $commission)
    {
        //
    }
}
