<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\Customer;
// use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class OutstandingController extends Controller
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
        $arr['customers'] = Customer::orderBy('customer_name', 'asc')->get();
        return view('admin.reports.outstanding.index')->with($arr);
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
        if ($request->customer == 'ALL'){
            $outstanding = array();
            $today = Carbon::now();
            $invoice = DB::table('invoices')->where('dueAmount', '>', 100)->orderBy('created_at', 'desc')->get();
            for ($i = 0; $i < count($invoice); $i++){
                $outstanding[$i]['type'] = "inventory";
                $outstanding[$i]['id'] = $invoice[$i]->id;
                $outstanding[$i]['customer_name'] = $invoice[$i]->customer_name;
                $outstanding[$i]['dueAmount'] = $invoice[$i]->dueAmount;
                $outstanding[$i]['Date'] = Carbon::parse($invoice[$i]->created_at)->format('Y-m-d');
                $outstanding[$i]['days'] = $today->diffInDays($invoice[$i]->created_at);
            }
            $jobs = DB::table('jobs')->where('dueAmount', '>', 100)->orderBy('created_at', 'desc')->get();
            for ($j = 0; $j < count($jobs); $j++){
                $outstanding[$i + $j]['type'] = "Repair";
                $outstanding[$i + $j]['id'] = $jobs[$j]->id;
                $outstanding[$i + $j]['customer_name'] = $jobs[$j]->customer_name;
                $outstanding[$i + $j]['dueAmount'] = $jobs[$j]->dueAmount;
                $outstanding[$i + $j]['Date'] = Carbon::parse($jobs[$j]->created_at)->format('Y-m-d');
                $outstanding[$i + $j]['days'] = $today->diffInDays($jobs[$j]->created_at);
            }
            $keys = array_column($outstanding, 'customer_name');
            array_multisort($keys, SORT_DESC, $outstanding);

            $arr['outstanding'] = $outstanding;
            return view('admin.reports.outstanding.total')->with($arr);
        }
        if (DB::table('customers')->where('customer_name', $request->customer)->exists()) {

            $outstanding = array();
            $today = Carbon::now();
            $invoice = DB::table('invoices')->where([
                ['customer_name', $request->customer],
                ['dueAmount', '>', 100],
            ])->get();
            for ($i = 0; $i < count($invoice); $i++){
                $outstanding[$i]['type'] = "inventory";
                $outstanding[$i]['id'] = $invoice[$i]->id;
                $outstanding[$i]['customer_name'] = $invoice[$i]->customer_name;
                $outstanding[$i]['dueAmount'] = $invoice[$i]->dueAmount;
                $outstanding[$i]['Date'] = Carbon::parse($invoice[$i]->created_at)->format('Y-m-d');
                $outstanding[$i]['days'] = $today->diffInDays($invoice[$i]->created_at);
            }
            $jobs = DB::table('jobs')->where([
                ['customer_name', $request->customer],
                ['dueAmount', '>', 100],
            ])->get();

            for ($j = 0; $j < count($jobs); $j++){
                $outstanding[$i + $j]['type'] = "Repair";
                $outstanding[$i + $j]['id'] = $jobs[$j]->id;
                $outstanding[$i + $j]['customer_name'] = $jobs[$j]->customer_name;
                $outstanding[$i + $j]['dueAmount'] = $jobs[$j]->dueAmount;
                $outstanding[$i + $j]['Date'] = Carbon::parse($jobs[$j]->created_at)->format('Y-m-d');
                $outstanding[$i + $j]['days'] = $today->diffInDays($jobs[$j]->created_at);
            }

            $totalInventory = DB::table('invoices')->where([
                ['customer_name', $request->customer],
                ['dueAmount', '>', 100],
            ])->sum('dueAmount');

            $totalRepair = DB::table('jobs')->where([
                ['customer_name', $request->customer],
                ['dueAmount', '>', 100],
            ])->sum('dueAmount');

            $arr['customer_name'] = $request->customer;
            $arr['total'] = $totalRepair + $totalInventory;

            $keys = array_column($outstanding, 'days');
            array_multisort($keys, SORT_DESC, $outstanding);

            $arr['outstanding'] = $outstanding;

            $pdf = PDF::loadView('admin.reports.outstanding.customerPrint', $arr);
            return $pdf->download('Outstanding.pdf');
        } else {
            $arr['customers'] = Customer::orderBy('customer_name', 'asc')->get();
            Session::flash('error', 'Need to select customer or ALL');
            return view('admin.reports.outstanding.index')->with($arr);
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
