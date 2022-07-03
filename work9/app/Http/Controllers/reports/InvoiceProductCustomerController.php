<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\inventory\Invoice;
use App\Models\inventory\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use function GuzzleHttp\Promise\each;

class InvoiceProductCustomerController extends Controller
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
        $arr['products'] = Product::select('product_name')->orderBy('product_name', 'asc')->get();
        $arr['customers'] = Customer::orderBy('customer_name', 'asc')->get();
        return view('admin.reports.invoiceProductCustomer.index')->with($arr);
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
        $arr['products'] = Product::select('product_name')->orderBy('product_name', 'asc')->get();
        $arr['customers'] = Customer::orderBy('customer_name', 'asc')->get();

        if ($request->customer_name == null && $request->product_name == null) {
            Session::flash('error', 'You need to select customer or Product');
            return view('admin.reports.invoiceProductCustomer.show')->with($arr);
        }

        if ($request->customer_name == null) {
            $productID = Product::where('product_name', $request->product_name)->value('id');
            $arr['invoicedetails'] = DB::table('invoice_details')
                ->where('product_id', '=', $productID)
                ->get();
            for ($i = 0; $i < count($arr['invoicedetails']); $i++) {
                $arr['invoicedetails'][$i]->customer_name = DB::table('invoices')
                    ->where('id', $arr['invoicedetails'][$i]->invoice_id)
                    ->value('customer_name');
            }
            return view('admin.reports.invoiceProductCustomer.show')->with($arr);
        }

        if ($request->product_name == null) {
            $cus_ID = Customer::where('customer_name', $request->customer_name)->value('id');
            $invoiceIDs = Invoice::where('customer_id', $cus_ID)->pluck('id');
            $arr['invoicedetails'] = DB::table('invoice_details')
                ->whereIn('invoice_id', $invoiceIDs)
                ->get();
            for ($i = 0; $i < count($arr['invoicedetails']); $i++) {
                $arr['invoicedetails'][$i]->customer_name = DB::table('invoices')
                    ->where('id', $arr['invoicedetails'][$i]->invoice_id)
                    ->value('customer_name');
            }
            return view('admin.reports.invoiceProductCustomer.show')->with($arr);
        }

        $cus_ID = Customer::where('customer_name', $request->customer_name)->value('id');
        $invoiceIDs = Invoice::where('customer_id', $cus_ID)->pluck('id');
        $productID = Product::where('product_name', $request->product_name)->value('id');
        $arr['invoicedetails'] = DB::table('invoice_details')
            ->whereIn('invoice_id', $invoiceIDs)
            ->where('product_id', '=', $productID)
            ->get();
        for ($i = 0; $i < count($arr['invoicedetails']); $i++) {
            $arr['invoicedetails'][$i]->customer_name = DB::table('invoices')
                ->where('id', $arr['invoicedetails'][$i]->invoice_id)
                ->value('customer_name');
        }
        return view('admin.reports.invoiceProductCustomer.show')->with($arr);
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
