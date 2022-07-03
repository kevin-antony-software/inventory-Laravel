<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use App\Models\inventory\InvoiceDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class InvoiceDetailsController extends Controller
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

        $invoiceDetails = InvoiceDetails::all();
        $invoice = DB::table('invoices')->get();

        for ($i = 0; $i < count($invoiceDetails); $i++) {
            foreach ($invoice as $inv) {
                if ($inv->id == $invoiceDetails[$i]->invoice_id) {
                    $invoiceDetails[$i]->customer_name = $inv->customer_name;
                    break;
                }
            }
        }

        $arr['invoiceDetails'] = $invoiceDetails;
        return view('admin.reports.invoiceDetails.index')->with($arr);
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
     * @param  \App\Models\inventory\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceDetails $invoiceDetails)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\inventory\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceDetails $invoiceDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\inventory\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceDetails $invoiceDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\inventory\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceDetails $invoiceDetails)
    {
        //
    }
}
