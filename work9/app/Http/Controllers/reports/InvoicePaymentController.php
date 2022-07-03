<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\inventory\Invoice;
use App\Models\technical\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class InvoicePaymentController extends Controller
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
        $arr['invoices'] = Invoice::orderBy('id', 'asc')->get();
        $arr['jobs'] = Job::orderBy('id', 'asc')->get();
        return view('admin.reports.invoicePayment.index')->with($arr);
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
        if ($request->flexRadioDefault ==  "inv") {
            if ($request->invoiceID == "") {
                return redirect()->route('InvoicePayment.index')->with('error', 'need to select invoice id')->withInput();
            } else {
                $arr['type'] = "Invoice";
                $arr['payments'] = DB::table('invoice_payment')->where('invoice_id', $request->invoiceID)->get();
                return view('admin.reports.invoicePayment.show')->with($arr);
            }
        } elseif ($request->flexRadioDefault ==  "job") {
            if ($request->jobID == "") {
                return redirect()->route('InvoicePayment.index')->with('error', 'need to select Job id')->withInput();
            } else {
                $arr['type'] = "Repair";
                $arr['payments'] = DB::table('invoice_payment')->where('job_id', $request->jobID)->get();
                return view('admin.reports.invoicePayment.show')->with($arr);
            }
        }
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
