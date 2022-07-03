<?php

namespace App\Http\Controllers\inventory;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\inventory\GRN;
use App\Models\inventory\GRNDetails;
use App\Models\inventory\Inventory;
use App\Models\inventory\Product;
use App\Models\inventory\Warehouse;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class GRNController extends Controller
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
        $arr['GRNDetails'] = DB::table('grn_details')->where('grn_id', $id)->get();
        $arr['grn'] = DB::table('grn')->where('id', $id)->first();
        $pdf = PDF::loadView('admin.inventoryViews.grn.myPDF', $arr);
        return $pdf->download('grn.pdf');
    }

    public function index()
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }

        $arr['grn'] = DB::table('grn')->orderBy('id', 'desc')->paginate(15);
        return view('admin.inventoryViews.grn.index')->with($arr);
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

        $arr['warehouses'] = Warehouse::all();
        $arr['inventories'] = Inventory::all();
        $arr['products'] = DB::table('products')->select('product_name', 'id')->get();
        return view('admin.inventoryViews.grn.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, GRN $grn)
    {
        $validatedData = $request->validate([
            'Fromwarehouse_id' => 'required',
            'Towarehouse_id' => 'required',
        ]);
        for ($q = 1; $q < 21; $q++) {
            $quantity = "quantity" . $q;
            $itemNo = "itemNo" . $q;
            $itemName =  "itemName" . $q;
            if ($request->$itemName != "") {
                $validatedData1 = $request->validate([
                    $itemNo => 'required',
                    $quantity => 'required|numeric',
                ]);
            }
        }

        $FromWarehouseName = Warehouse::where('id', $request->Fromwarehouse_id)->first()->warehouse_name;
        $ToWarehouseName = Warehouse::where('id', $request->Towarehouse_id)->first()->warehouse_name;
        $statement = DB::select("SHOW TABLE STATUS LIKE 'grn'");
        $nextId = $statement[0]->Auto_increment;

        for ($q = 1; $q < 21; $q++) {

            $quantity = "quantity" . $q;
            $itemNo = "itemNo" . $q;
            $itemName =  "itemName" . $q;

            if ($request->$itemName != "") {
                $GRNDetails = new GRNDetails();
                $GRNDetails->grn_id = $nextId;
                $GRNDetails->product_id = $request->$itemNo;
                $GRNDetails->product_name = $request->$itemName;
                $GRNDetails->qty = $request->$quantity;
                $GRNDetails->save();
            }
        }
        $grn->FromWarehouse_ID = $request->Fromwarehouse_id;
        $grn->FromWarehouse_name = $FromWarehouseName;
        $grn->ToWarehouse_ID = $request->Towarehouse_id;
        $grn->ToWarehouse_name = $ToWarehouseName;
        $user = auth()->user();
        $grn->user_ID = $user->id;
        $grn->user_name = $user->name;
        $grn->status = "not confirmed";
        $grn->save();
        return redirect()->route('grn.index')->with('message', 'GRN was Created!');;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\inventory\GRN  $gRN
     * @return \Illuminate\Http\Response
     */
    public function show(GRN $grn)
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }
        $arr['grn'] = $grn;
        $arr['grnDetails'] = DB::table('grn_details')->where('grn_id', $grn->id)->get();

        return view('admin.inventoryViews.grn.show')->with($arr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\inventory\GRN  $gRN
     * @return \Illuminate\Http\Response
     */
    public function edit(GRN $grn)
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }
        if ($grn->status != 'Received') {
            $arr['grn'] = $grn;
            $arr['GRNDetails'] = GRNDetails::where('grn_id', $grn->id)->get();
            return view('admin.inventoryViews.grn.edit')->with($arr);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\inventory\GRN  $gRN
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GRN $grn)
    {
        $count = GRNDetails::where('grn_id', $grn->id)->count();

        for ($q = 1; $q <= $count; $q++) {
            $quantity = "quantity_" . $q;
            $Aquantity = "Aquantity_" . $q;
            $itemNo = "itemNo_" . $q;
            $itemName =  "itemName_" . $q;

            $validatedData = $request->validate([
                $Aquantity => 'required|numeric',
            ]);
        }

        for ($q = 1; $q <= $count; $q++) {
            $quantity = "quantity_" . $q;
            $Aquantity = "Aquantity_" . $q;
            $itemNo = "itemNo_" . $q;
            $itemName =  "itemName_" . $q;

            if ($request->$quantity != $request->$Aquantity) {
                $affected = DB::table('grn_details')
                    ->where('grn_id', $grn->id)
                    ->where('product_id', $request->$itemNo)
                    ->update([
                        'qty' => $request->$Aquantity,
                    ]);
            }

            if (DB::table('inventories')
                ->where('inventories.product_id', $request->$itemNo)
                ->where('inventories.warehouse_id', $grn->ToWarehouse_ID)->exists()
            ) {
                $affected1 = DB::table('inventories')
                    ->where('inventories.product_id', $request->$itemNo)
                    ->where('inventories.warehouse_id', $grn->ToWarehouse_ID)
                    ->increment('qty', $request->$Aquantity);

                $affected2 = DB::table('inventories')
                    ->where('inventories.product_id', $request->$itemNo)
                    ->where('inventories.warehouse_id', $grn->FromWarehouse_ID)
                    ->decrement('qty', $request->$Aquantity);

            } else {
                $product = DB::table('products')->where('id', $request->$itemNo)->first();

                DB::table('inventories')->insert([
                    'product_id' => $request->$itemNo,
                    'product_name' => $request->$itemName,
                    'price' => $product->price,
                    'cost' => $product->totalcost,
                    'category_id' => $product->category_id,
                    'category_name' => $product->category_name,
                    'warehouse_id' => $grn->ToWarehouse_ID,
                    'warehouse_name' => $grn->ToWarehouse_name,
                    'qty' => $request->$Aquantity,
                ]);

                $affected3 = DB::table('inventories')
                ->where('inventories.product_id', $request->$itemNo)
                ->where('inventories.warehouse_id', $grn->FromWarehouse_ID)
                ->decrement('qty', $request->$Aquantity);

            }
        }

        $grn->status = "Received";
        $grn->save();
        return redirect()->route('grn.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\inventory\GRN  $gRN
     * @return \Illuminate\Http\Response
     */
    public function destroy(GRN $grn)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        if ($grn->status != 'Received') {
            DB::table('grn_details')->where('grn_id', $grn->id)->delete();
            $grn->delete();
            return redirect()->route('grn.index')->with('message', 'GRN Deleted');
        } else {
            return redirect()->route('grn.index')->with('error', 'Cant Delete');
        }
    }
}
