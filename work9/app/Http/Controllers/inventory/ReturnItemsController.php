<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\inventory\Invoice;
use App\Models\inventory\InvoiceDetails;
use App\Models\inventory\Product;
use App\Models\inventory\ReturnItems;
use App\Models\inventory\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReturnItemsController extends Controller
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
        $arr['returnItems'] = ReturnItems::paginate(30);
        return view('admin.inventoryViews.returnItems.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    function fetch($id)
    {
        $customerID = $id;
        $results = Invoice::where('customer_id', $customerID)->get();

        foreach ($results as $c) {
            echo '<option value="' . $c["id"] . '"> ' . $c["id"] . ' </option> ';
        }
    }

    function giveInvoiceDetails($id)
    {
        $results = InvoiceDetails::where('invoice_id', $id)->get();
        $i = 1;
        echo "<div pt-2'> <table class='table table-bordered'>
        <tr style = 'border: 1px solid black; padding: 5px;'>
            <th>invoice ID</th>
            <th>product ID</th>
            <th>product name</th>
            <th>qty</th>
            <th>Return qty</th>
        </tr>";
        foreach ($results as $row) {
            echo "<tr>";
            echo "<td width=10%> " . $id . "</td>";
            echo "<td width=10%> <input readonly class='form-control' type='number' name='productID" . $i . "' id='productID" . $i . "' value = '" . $row['product_id'] . "'></td>";
            echo "<td> <input readonly type='text' name='productName" . $i . "' id='productID" . $i . "' value='" . $row['product_name'] . "'></td>";
            echo "<td>" . $row['qty'] . "</td>";
            echo "<td> <input class='form-control' type='number' name='returnQty" . $i . "' id='returnQty" . $i . "'> </td>";
            echo "</tr>";
            $i++;
        }
        echo "</table>";
        echo "<button class='btn btn-primary btn-block' type='submit'>Save</button>";
    }

    public function create()
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['customers'] = Customer::select('id', 'customer_name')->orderBy('customer_name', 'asc')->get();
        $arr['warehouses'] = Warehouse::all();
        return view('admin.inventoryViews.returnItems.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer_name = Customer::where('id', $request->customer)->value('customer_name');
        $warehouse_name = Warehouse::where('id', $request->Warehouse)->value('warehouse_name');
        $invoice = Invoice::where('id', $request->InvoiceID)->first();
        $oldTotal = $invoice->total;
        $reduceTotal = 0;
        $invoiceDetails = InvoiceDetails::where('invoice_id', $request->InvoiceID)->get();
        $CountInvoiceDetails = count($invoiceDetails);

        for ($i = 1; $i <= $CountInvoiceDetails; $i++) {
            $returnedQty = "returnQty" . $i;
            $productID = "productID" . $i;
            $product_name = "productName" . $i;

            if (!empty($request->$productID)) {
                $priceAfterDiscount = $invoiceDetails[$i - 1]->priceAfterDiscount;
                $reduceAmount = $priceAfterDiscount * $request->$returnedQty;
                $reduceTotal = $reduceTotal + $reduceAmount;
                $product = product::where('id', $request->$productID)->first();

                //insert a return item
                $returnItem = new ReturnItems();
                $returnItem->customer_id = $request->customer;
                $returnItem->customer_name = $customer_name;
                $returnItem->invoice_id = $request->InvoiceID;
                $returnItem->warehouse_id = $request->Warehouse;
                $returnItem->warehouse_name = $warehouse_name;
                $returnItem->product_id = $request->$productID;
                $returnItem->product_name = $request->$product_name;
                $returnItem->qty = $request->$returnedQty;
                $returnItem->price_of_each = $priceAfterDiscount;
                $returnItem->old_total = $oldTotal;
                $returnItem->new_total = $oldTotal;
                $returnItem->save();

                //update invoice details
                $affected = DB::table('invoice_details')
                    ->where('invoice_id', $request->InvoiceID)
                    ->where('product_id', $request->$productID)
                    ->decrement('qty', $request->$returnedQty);

                $affected = DB::table('invoice_details')
                    ->where('invoice_id', $request->InvoiceID)
                    ->where('product_id', $request->$productID)
                    ->decrement('subtotal_price', $reduceAmount);

                //update invetories
                if (DB::table('inventories')
                    ->where('product_id', $request->$productID)
                    ->where('warehouse_id', $request->Warehouse)->exists()
                ) {
                    $affected2 = DB::table('inventories')
                        ->where('product_id', $request->$productID)
                        ->where('warehouse_id', $request->Warehouse)
                        ->increment('qty', $request->$returnedQty);
                } else {
                    DB::table('inventories')->insert([
                        'product_id' => $product->id,
                        'product_name' => $product->product_name,
                        'price' => $product->price,
                        'cost' => $product->totalcost,
                        'category_id' => $product->category_id,
                        'category_name' => $product->category_name,
                        'warehouse_id' => $request->Warehouse,
                        'warehouse_name' => $warehouse_name,
                        'qty' => $request->$returnedQty,
                    ]);
                }
            }
        }
        
        $new1Total = $oldTotal - $reduceTotal;
        $NewNonVatTotal = $new1Total / 1.08;
        $newVat = $new1Total - $NewNonVatTotal;
        //update return items with new total in the invoice.
        DB::table('return_items')->where('invoice_id', $request->InvoiceID)->update(['new_total' => $new1Total]);
        //update invoice
        DB::table('invoices')->where('id', $request->InvoiceID)->update([
            'total' => $new1Total,
            'vatAmount' => $newVat,
        ]);
        DB::table('invoices')->where('id', $request->InvoiceID)->decrement('dueAmount', $reduceTotal);
        return redirect()->route('returnItems.index')->with('message', 'Return items Updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\inventory\ReturnItems  $returnItems
     * @return \Illuminate\Http\Response
     */
    public function show(ReturnItems $returnItems)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\inventory\ReturnItems  $returnItems
     * @return \Illuminate\Http\Response
     */
    public function edit(ReturnItems $returnItems)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\inventory\ReturnItems  $returnItems
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReturnItems $returnItems)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\inventory\ReturnItems  $returnItems
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReturnItems $returnItems)
    {
        //
    }
}
