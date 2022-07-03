<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\inventory\Category;
use App\Models\inventory\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class InventoryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('home');
        }
        $arr['warehouses'] = Warehouse::all();
        $arr['categories'] = Category::all();

        return view('admin.reports.inventory.index')->with($arr);
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
        $arr['selected'] = $request->warehouse_id;

        if ($request->warehouse_id == "Total") {
            if ($request->category_id == 'All') {
                $arr['stock'] = DB::table('inventories')
                    ->select('product_id', 'product_name', DB::raw('SUM(qty) as qty'))
                    ->groupBy('product_id', 'product_name')
                    ->get();
                return view('admin.reports.inventory.show')->with($arr);
            } else {
                $arr['stock'] = DB::table('inventories')
                    ->select('product_id', 'product_name', DB::raw('SUM(qty) as qty'))
                    ->groupBy('product_id', 'product_name')
                    ->where('category_id', $request->category_id)
                    ->get();
                return view('admin.reports.inventory.show')->with($arr);
            }
        } elseif ($request->warehouse_id == "All") {
            if ($request->category_id == 'All') {
                $arr['stock'] = DB::table('inventories')
                    ->select('warehouse_id', 'warehouse_name', 'product_id', 'product_name', 'qty')
                    ->orderBy('warehouse_name', 'desc')
                    ->orderBy('product_name', 'desc')
                    ->get();
                return view('admin.reports.inventory.show')->with($arr);
            } else {
                $arr['stock'] = DB::table('inventories')
                    ->select('warehouse_id', 'warehouse_name', 'product_id', 'product_name', 'qty')
                    ->orderBy('warehouse_id', 'desc')
                    ->orderBy('product_name', 'desc')
                    ->where('category_id', $request->category_id)
                    ->get();
                return view('admin.reports.inventory.show')->with($arr);
            }
        } else {
            if ($request->category_id == 'All') {
                $arr['stock'] = DB::table('inventories')
                    ->select('warehouse_id', 'warehouse_name', 'product_id', 'product_name', 'qty')
                    ->where('warehouse_id', $request->warehouse_id)
                    ->get();
                return view('admin.reports.inventory.show')->with($arr);
            } else {
                $arr['stock'] = DB::table('inventories')
                    ->select('warehouse_id', 'warehouse_name', 'product_id', 'product_name', 'qty')
                    ->where('warehouse_id', $request->warehouse_id)
                    ->where('category_id', $request->category_id)
                    ->get();
                return view('admin.reports.inventory.show')->with($arr);
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
