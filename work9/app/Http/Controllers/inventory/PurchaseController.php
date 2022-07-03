<?php

namespace App\Http\Controllers\inventory;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\inventory\Warehouse;
use App\Models\inventory\Purchase;
use App\Models\inventory\Product;
use App\Models\inventory\PurchaseDetails;
use App\Models\inventory\Category;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;


class PurchaseController extends Controller
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
    public function generatePDF($id)
    {
        $arr['purchaseDetails'] = DB::table('purchase_details')->where('purchase_id', $id)->get();
        $pdf = PDF::loadView('admin.inventoryViews.purchase.myPDF', $arr);
        return $pdf->download('PurchaseOrder.pdf');
    }

    public function index()
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }

        $arr['purchases'] = Purchase::orderBy('id', 'desc')->paginate(15);
        return view('admin.inventoryViews.purchase.index')->with($arr);
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

        $arr['warehouses'] = Warehouse::all();
        $arr['products'] = Product::all();
        return view('admin.inventoryViews.purchase.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Purchase $Purchase)
    {
        $validatedData = $request->validate([
            'warehouse_id' => 'required',
        ]);

        $WarehouseName = Warehouse::where('id', $request->warehouse_id)->first()->warehouse_name;

        $statement = DB::select("SHOW TABLE STATUS LIKE 'purchases'");
        $nextId = $statement[0]->Auto_increment;
        $PO = $nextId;

        $total_cost = 0;
        $total_USD = 0;
        $total_price = 0;

        for ($q = 1; $q < 21; $q++) {
            $quantity = "quantity_" . $q;
            $itemNo = "itemNo_" . $q;
            $itemName =  "itemName_" . $q;
            $USDcost = "USDcost_" . $q;
            $USDrate = "USDrate_" . $q;
            $DFP = "DFP_" . $q;
            $price = "price_" . $q;

            if ($request->$itemName != "") {
                $validatedData1 = $request->validate([
                    $itemNo => 'required',
                    $quantity => 'required|numeric',
                    $USDcost => 'required|numeric',
                    $USDrate => 'required|numeric',
                    $DFP => 'required|numeric',
                    $price => 'required|numeric',
                ]);
            }
        }
        for ($q = 1; $q < 21; $q++) {
            $quantity = "quantity_" . $q;
            $itemNo = "itemNo_" . $q;
            $itemName =  "itemName_" . $q;
            $USDcost = "USDcost_" . $q;
            $USDrate = "USDrate_" . $q;
            $DFP = "DFP_" . $q;
            $price = "price_" . $q;

            if ($request->$itemName != "") {

                $purchaseDetails = new PurchaseDetails;
                $purchaseDetails->purchase_id = $PO;
                $purchaseDetails->product_id = $request->$itemNo;
                $purchaseDetails->product_name = $request->$itemName;
                $purchaseDetails->qty = $request->$quantity;
                $firstCost = $request->$USDcost * $request->$USDrate;
                $totalCost = $request->$USDcost * $request->$USDrate * (1 + ($request->$DFP) / 100);

                //dd ($request->$USDcost);
                $affectedProducts = DB::table('products')
                    ->where('id', $request->$itemNo)
                    ->update(
                        [
                            'USDcost' => $request->$USDcost,
                            'ExchangeUSDrate' => $request->$USDrate,
                            'firstCost' => $firstCost,
                            'DFP' => $request->$DFP,
                            'totalcost' => $totalCost,
                            'price' => $request->$price
                        ]
                    );
                $purchaseDetails->subtotal_price = $request->$price * $request->$quantity;
                $purchaseDetails->subtotal_cost = ($request->$USDcost * $request->$USDrate * (1 + ($request->$DFP) / 100)) * $request->$quantity;
                $total_price =  $total_price + $request->$price * $request->$quantity;
                $total_USD = $total_USD + $request->$USDcost * $request->$quantity;
                $total_cost = $total_cost + ($request->$USDcost * $request->$USDrate * (1 + ($request->$DFP) / 100)) * $request->$quantity;
                $purchaseDetails->save();
            }
        }
        $Purchase->warehouse_ID = $request->warehouse_id;
        $Purchase->warehouse_name = $WarehouseName;
        $user = auth()->user();
        $Purchase->user_ID = $user->id;
        $Purchase->user_name = $user->name;
        $Purchase->status = "not confirmed";
        $Purchase->total_cost = $total_cost;
        $Purchase->total_USD = $total_USD;
        $Purchase->total_price = $total_price;
        $Purchase->save();
        return redirect()->route('purchase.index')->with('message', 'Purchase was Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }
        $arr['purchase'] = $purchase;

        return view('admin.inventoryViews.purchase.show')->with($arr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }
        if ($purchase->status != 'Received') {
            $arr['purchase'] = $purchase;
            $arr['purchaseDetails'] = PurchaseDetails::where('purchase_id', $purchase->id)->get();
            return view('admin.inventoryViews.purchase.edit')->with($arr);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        $count = PurchaseDetails::where('purchase_id', $purchase->id)->count();

        for ($q = 1; $q <= $count; $q++) {
            $quantity = "quantity_" . $q;
            $Aquantity = "Aquantity_" . $q;
            $itemNo = "itemNo_" . $q;
            $itemName =  "itemName_" . $q;

            $validatedData = $request->validate([

                $Aquantity => 'required|numeric',
                $itemNo => 'required|numeric',

            ]);
        }

        $total_cost = 0;
        $total_USD = 0;
        $total_price = 0;

        for ($q = 1; $q <= $count; $q++) {
            $quantity = "quantity_" . $q;
            $Aquantity = "Aquantity_" . $q;
            $itemNo = "itemNo_" . $q;
            $itemName =  "itemName_" . $q;


            $tempPrice = Product::where('id', $request->$itemNo)->value('price');
            $temp_USD = Product::where('id', $request->$itemNo)->value('USDcost');
            $temp_Cost = Product::where('id', $request->$itemNo)->value('totalcost');

            if ($request->$quantity != $request->$Aquantity) {
                $affected = DB::table('purchase_details')
                    ->where('purchase_id', $purchase->id)
                    ->where('product_id', $request->$itemNo)
                    ->update([
                        'qty' => $request->$Aquantity,
                        'subtotal_price' => $tempPrice * $request->$Aquantity,
                        'subtotal_cost' => $temp_Cost * $request->$Aquantity,
                    ]);
            }
            $total_price =  $total_price + $tempPrice * $request->$Aquantity;
            $total_USD = $total_USD + $temp_USD * $request->$Aquantity;
            $total_cost = $total_cost + $temp_Cost * $request->$Aquantity;

            if (DB::table('inventories')
                ->where('inventories.product_id', $request->$itemNo)
                ->where('inventories.warehouse_id', $request->warehouse_id)->exists()
            ) {
                $affected = DB::table('inventories')
                    ->where('inventories.product_id', $request->$itemNo)
                    ->where('inventories.warehouse_id', $request->warehouse_id)
                    ->increment('qty', $request->$Aquantity);

                $affected1 = DB::table('inventories')
                    ->where('inventories.product_id', $request->itemNo)
                    ->update(['price' => $tempPrice]);

                $affected2 = DB::table('inventories')
                    ->where('inventories.product_id', $request->itemNo)
                    ->update(['cost' => $temp_Cost]);
            } else {
                DB::table('inventories')->insert([
                    'product_id' => $request->$itemNo,
                    'product_name' => $request->$itemName,
                    'price' => $tempPrice,
                    'cost' => $temp_Cost,
                    'category_id' => DB::table('products')->where('id', $request->$itemNo)->value('category_id'),
                    'category_name' => DB::table('products')->where('id', $request->$itemNo)->value('category_name'),
                    'warehouse_id' => $request->warehouse_id,
                    'warehouse_name' => $purchase->warehouse_name,
                    'qty' => $request->$Aquantity,
                ]);
            }
        }

        $purchase->status = "Received";
        $purchase->total_cost = $total_cost;
        $purchase->total_USD = $total_USD;
        $purchase->total_price = $total_price;
        $purchase->save();
        return redirect()->route('purchase.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        if ($purchase->status != 'Received') {
            DB::table('purchase_details')->where('purchase_id', $purchase->id)->delete();
            $purchase->delete();
            return redirect()->route('purchase.index')->with('message', 'Purchase Deleted');
        } else {
            return redirect()->route('purchase.index')->with('error', 'Cant Delete');
        }
    }
}
