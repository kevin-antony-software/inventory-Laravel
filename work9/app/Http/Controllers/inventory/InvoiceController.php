<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\financials\commission;
use App\Models\inventory\Inventory;
use App\Models\inventory\Invoice;
use App\Models\inventory\InvoiceDetails;
use App\Models\inventory\Product;
use App\Models\inventory\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function print($id)
    {
        $arr['invoiceDetails'] = DB::table('invoice_details')->where('invoice_id', $id)->get();
        $arr['invoice'] = DB::table('invoices')->where('id', $id)->first();
        $arr['city'] = DB::table('customers')->where(
            'id',
            DB::table('invoices')->where('id', $id)->value('customer_id')

        )->value('city');
        $pdf = PDF::loadView('admin.inventoryViews.invoice.print', $arr);

        return $pdf->download('invoice.pdf');
    }
    public function printVAT($id)
    {
        $arr['invoiceDetails'] = DB::table('invoice_details')->where('invoice_id', $id)->get();
        $arr['invoice'] = DB::table('invoices')->where('id', $id)->first();
        $arr['city'] = DB::table('customers')->where(
            'id',
            DB::table('invoices')->where('id', $id)->value('customer_id')

        )->value('city');
        $arr['customerVATID'] = DB::table('customers')->where(
            'id',
            DB::table('invoices')->where('id', $id)->value('customer_id')

        )->value('VATNumber');
        $pdf = PDF::loadView('admin.inventoryViews.invoice.printVAT', $arr);
        return $pdf->download('invoiceVAT.pdf');
    }
    public function invoiceSummary()
    {
        $arr['invoices'] = DB::table('invoices')->get();
        return view('admin.inventoryViews.invoice.invoiceAll')->with($arr);
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

        $arr['invoices'] = Invoice::orderBy('id', 'desc')->paginate(15);
        return view('admin.inventoryViews.invoice.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('dashboard');
        }
        $arr['customers'] = Customer::select('id', 'customer_name')->orderBy('customer_name', 'asc')->get();
        $arr['warehouses'] = DB::table('user_warehouse')->where('user_id', auth()->user()->id)->get();
        $arr['inventories'] = Inventory::all();
        $arr['product'] = Product::select('id', 'product_name', 'price')->get();
        $arr['user'] = auth()->user();
        return view('admin.inventoryViews.invoice.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('dashboard');
        }

        $validatedData = $request->validate([
            'customer_name' => 'required',
            'warehouse_id' => 'required',
            'totalAftertax' => 'required',
        ]);

        for ($i = 1; $i < 21; $i++) {
            $itemNo = "itemNo" . $i;
            $name = 'itemName' . $i;
            $price = "price" . $i;
            $dp1 = "dprice" . $i;
            $avilableQuantity = "aquantity" . $i;
            $quantity1 = "quantity" . $i;
            $total = 'total' . $i;
            if ($request->$name != "") {
                $validatedData = $request->validate([
                    $itemNo => 'required',
                    $dp1 => 'required',
                    $quantity1 => 'required',
                    $total => 'required',
                    $quantity1 => 'required|lte:' . $avilableQuantity,
                ]);
            }
            if ($request->$itemNo != "") {
                if ($request->$quantity1 == '0') {
                    return redirect()->route('invoice.create')->with('error', 'qty cant be zero')->withInput();
                }
                $affected1 = DB::table('inventories')
                    ->where('inventories.product_id', $request->$itemNo)
                    ->where('inventories.warehouse_id', $request->warehouse_id)
                    ->first();

                $temp = $affected1->qty - $request->$quantity1;
                if ($temp < 0) {
                    return redirect()->route('invoice.create')->with('error', 'not enough stock - ' . $request->name)->withInput();
                }
            }
        }

        $customer = Customer::where('customer_name', $request->customer_name)->first();
        $customer_id = $customer->id;

        $TotalOutstanding = DB::table('invoices')
            ->where('customer_id', $customer_id)
            ->sum('dueAmount');

        $TotalchequesInHand = DB::table('cheques')
            ->where([
                ['customer_id', '=', $customer_id],
                ['status', '=', 'pending'],
            ])->sum('amount');

        $customerLimit = $customer->creditLimit;
        $customerChequeLimit = DB::table('customers')
            ->where('id', $customer_id)
            ->value('chequeLimit');

        if (($TotalOutstanding + $request->totalAftertax) > $customerLimit) {
            return redirect()->route('invoice.create')->with('error', 'Total Amount Exceeds to Outstanding Limit')->withInput();
        }
        //cheques limit check
        if ($TotalchequesInHand > $customerChequeLimit) {
            return redirect()->route('invoice.create')->with('error', 'Total cheques in Hand, exceeds cheques limit')->withInput();
        }
        $idIN = DB::select("SHOW TABLE STATUS LIKE 'invoices'");
        $next_id = $idIN[0]->Auto_increment;

        for ($i = 1; $i < 21; $i++) {
            $itemNo = "itemNo" . $i;
            $dp1 = "dprice" . $i;
            $price = "price" . $i;
            $quantity1 = "quantity" . $i;
            $total = 'total' . $i;
            $name = 'itemName' . $i;
            if ($request->$itemNo != "") {
                $invoiceDetail = new InvoiceDetails();
                $invoiceDetail->invoice_id = $next_id;
                $invoiceDetail->product_id = $request->$itemNo;
                $invoiceDetail->product_name = $request->$name;
                $invoiceDetail->qty = $request->$quantity1;
                $invoiceDetail->price = $request->$price;
                $invoiceDetail->discountPercentage = $request->$dp1;

                $invoiceDetail->priceAfterDiscount = ($request->$price) * (1 - (($request->$dp1) / 100));
                if ($request->$dp1 > 0) {
                    $invoiceDetail->subtotal_price = ($request->$price) * (1 - (($request->$dp1) / 100)) * ($request->$quantity1);
                } elseif ($request->$dp1 == 0) {
                    $invoiceDetail->subtotal_price = $request->$price * $request->$quantity1;
                }

                $invoiceDetail->save();
                $affected = DB::table('inventories')
                    ->where('inventories.product_id', $request->$itemNo)
                    ->where('inventories.warehouse_id', $request->warehouse_id)
                    ->decrement('qty', $request->$quantity1);
            }
        }
        $invoice = new Invoice();
        $invoice->warehouse_ID = $request->warehouse_id;
        $invoice->warehouse_name = Warehouse::where('id', $request->warehouse_id)->first()->warehouse_name;
        $invoice->customer_ID = $customer_id;
        $invoice->customer_name = Customer::where('id', $customer_id)->first()->customer_name;
        $invoice->total = $request->totalAftertax;
        $invoice->vatAmount = (($request->totalAftertax) - (($request->totalAftertax) / 1.12));
        $invoice->payed = 0;
        $invoice->dueAmount = $request->totalAftertax;
        $invoice->totalCommision = 0;
        $invoice->commission_user_ID = Customer::where('id', $customer_id)->first()->owner_ID;
        $invoice->commission_owner = Customer::where('id', $customer_id)->first()->owner_name;
        $user = auth()->user();
        $invoice->user_ID = $user->id;
        $invoice->user_name = $user->name;
        $invoice->status = "not paid";
        $invoice->save();

        $now = Carbon::now();
        $month = $now->month;
        $year =  $now->year;

        if (DB::table('commission')->where([
            ['month', '=', $month],
            ['year', '=', $year],
            ['owner_id', '=', $customer->owner_ID],
        ])->doesntExist()) {
            $commission = new commission();
            $commission->month = $month;
            $commission->year = $year;
            $commission->owner_id = $customer->owner_ID;
            $commission->owner_name = $customer->owner_name;
            $commission->status = 'not paid';
            $commission->invoiceDueAmount = $request->totalAftertax;
            $commission->totalCommission = 0;
            $commission->paidCommission = 0;
            $commission->returnChequeCommission = 0;
            $commission->save();
        } else {
            DB::table('commission')
                ->where([
                    ['month', '=', $month],
                    ['year', '=', $year],
                    ['owner_id', '=', $customer->owner_ID],
                ])->increment('invoiceDueAmount', $request->totalAftertax);
        }

        return redirect()->route('invoice.index')->with('message', 'new invoice saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\inventory\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }
        $arr['invoice'] = $invoice;
        $arr['invoicedetails'] = DB::table('invoice_details')->where('invoice_id', $invoice->id)->get();

        return view('admin.inventoryViews.invoice.show')->with($arr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\inventory\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        if ($invoice->status != 'paid') {
            $arr['invoice'] = $invoice;
            $arr['invoiceDetails'] = DB::table('invoice_details')->where('invoice_id', $invoice->id)->get();
            return view('admin.inventoryViews.invoice.edit')->with($arr);
        } else {
            return redirect()->route('invoice.index')->with('message', 'this invoice is paid and cant be edited');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\inventory\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        for ($j = 1; $j < 15; $j++) {
            $quantity1 = "quantity" . $j;
            $IDID1 = "invoicedetailID" . $j;
            $invoiceQty = DB::table('invoice_details')->where('id', $request->$IDID1)->value('qty');
            $productID = DB::table('invoice_details')->where('id', $request->$IDID1)->value('product_id');

            if ($request->$quantity1) {
                $returnedQty = (int)$request->$quantity1;
                if ($invoiceQty < $returnedQty) {
                    return redirect()->route('invoice.edit', $invoice->id)->with('error', 'return qty cant be more than invoice qty')->withInput();
                }
            }
        }
        //executing return goods
        for ($i = 1; $i < 21; $i++) {
            $quantity1 = "quantity" . $i;
            $IDID1 = "invoicedetailID" . $i;
            $invoiceQty = DB::table('invoice_details')->where('id', $request->$IDID1)->value('qty');
            $productID = DB::table('invoice_details')->where('id', $request->$IDID1)->value('product_id');

            if ($request->$quantity1) {
                $returnedQty = (int)$request->$quantity1;

                //update the invoice details
                DB::table('invoice_details')->where('id', $request->$IDID1)->decrement('qty', $returnedQty);
                $warehouse = DB::table('invoices')->where('id', $invoice->id)->value('warehouse_id');
                //update subtotal of the invoice details
                $priceAfterDiscount = DB::table('invoice_details')->where('id', $request->$IDID1)->value('priceAfterDiscount');
                DB::table('invoice_details')->where('id', $request->$IDID1)->decrement('subtotal_price', $priceAfterDiscount *  $returnedQty);

                //update the due amount in the invoice
                DB::table('invoices')->where('id', $invoice->id)->decrement('total', ($priceAfterDiscount *  $returnedQty));
                DB::table('invoices')->where('id', $invoice->id)->decrement('dueAmount', ($priceAfterDiscount *  $returnedQty));

                //modify the inventory
                DB::table('inventories')
                    ->where('warehouse_id', $warehouse)
                    ->where('product_id', $productID)
                    ->increment('qty',  $returnedQty);
            }
        }

        return redirect()->route('invoice.index')->with('message', 'invoice was updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\inventory\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('home');
        }
        $arr =  DB::table('invoice_details')->where('invoice_id', $invoice->id)->get();
        foreach ($arr as $a) {
            $affected = DB::table('inventories')
                ->where('inventories.product_id', $a->product_id)
                ->where('inventories.warehouse_id', $invoice->warehouse_id)
                ->increment('qty', $a->qty);
        }
        $invoice->delete();
        return redirect()->route('invoice.index');
    }
}
