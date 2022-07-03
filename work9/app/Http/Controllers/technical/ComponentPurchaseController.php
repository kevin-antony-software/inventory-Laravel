<?php

namespace App\Http\Controllers\technical;

use App\Http\Controllers\Controller;
use App\Models\technical\Component;
use App\Models\technical\ComponentPurchase;
use App\Models\technical\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ComponentPurchaseController extends Controller
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
        $arr['purchases'] = ComponentPurchase::orderBy('id', 'desc')->paginate(25);
        return view('admin.technical.purchases.index')->with($arr);
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
        $arr['components'] = Component::orderBy('component_name', 'asc')->get();
        return view('admin.technical.purchases.create')->with($arr);
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

        for ($q = 1; $q < 31; $q++) {
            $quantity = "quantity_" . $q;
            $itemNo = "itemNo_" . $q;
            $itemName =  "itemName_" . $q;
            if ($request->$itemNo != "") {
                $validatedData = $request->validate([
                    $quantity => 'required|integer',
                    $itemName => 'required',
                ]);
            }
        }


        for ($q = 1; $q < 31; $q++) {
            $quantity = "quantity_" . $q;
            $itemNo = "itemNo_" . $q;
            $itemName =  "itemName_" . $q;
            $category =  "category_" . $q;
            $category_ID = "category_ID" . $q;

            if ($request->$itemNo != "") {

                $purchase = new ComponentPurchase();
                $purchase->component_id = $request->$itemNo;
                $purchase->component_name = $request->$itemName;
                $purchase->qty = $request->$quantity;
                $purchase->save();

                if (DB::table('stocks')
                    ->where('stocks.component_id', $request->$itemNo)
                    ->exists()
                ) {
                    $affected = DB::table('stocks')
                        ->where('stocks.component_id', $request->$itemNo)
                        ->increment('qty', $request->$quantity);
                } else {
                    $newStockItem = new Stock();
                    $newStockItem->component_id = $request->$itemNo;
                    $newStockItem->component_name = $request->$itemName;
                    $newStockItem->component_category_name = $request->$category;
                    $newStockItem->component_category_id = $request->$category_ID;
                    $newStockItem->qty = $request->$quantity;
                    $newStockItem->save();
                }
            }
        }
        return redirect()->route('componentPurchase.index')->with('message', 'new stock added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(ComponentPurchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('dashboard');
        }
        $arr['components'] = Component::orderBy('component_name', 'asc')->get();
        $arr['ComponentPurchase'] = DB::table('component_purchases')->where('id', $id)->first();
        return view('admin.technical.purchases.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $purchaseID)
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'component_name' => 'required',
            'component_id' => 'required',
            'qty' => 'required|numeric',
        ]);

        $purchase = DB::table('component_purchases')->where('id', $purchaseID)->first();

        $affected = DB::table('stocks')->where('component_id', $purchase->component_id)->decrement('qty', $purchase->qty);
        $affected1 = DB::table('component_purchases')
            ->where('id', $purchaseID)
            ->update([
                'component_id' => $request->component_id,
                'component_name' => $request->component_name,
                'qty' => $request->qty,
            ]);

        if (DB::table('stocks')
            ->where('stocks.component_id', $request->component_id)
            ->exists()
        ) {
            $affected2 = DB::table('stocks')
                ->where('stocks.component_id', $request->component_id)
                ->increment('qty', $request->qty);
        } else {
            $newStockItem = new Stock();
            $newStockItem->component_id = $request->component_id;
            $newStockItem->component_name = $request->component_name;
            $Newcomponent = DB::table('components')->where('id', $request->component_id)->first();
            $newStockItem->component_category_name = $Newcomponent->category_name;
            $newStockItem->component_category_id = $Newcomponent->category_id;
            $newStockItem->qty = $request->qty;
            $newStockItem->save();
        }

        return redirect()->route('componentPurchase.index')->with('message', 'updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComponentPurchase $purchase)
    {
        //
    }
}
