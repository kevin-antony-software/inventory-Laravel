<?php

namespace App\Http\Controllers\inventory;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\inventory\Warehouse;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;


class WarehouseController extends Controller
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
        $arr['warehouses'] = Warehouse::all();
        return view('admin.inventoryViews.warehouse.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('director-only')){
            return redirect()->route('dashboard');
        }
        return view('admin.inventoryViews.warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Warehouse $Warehouse)
    {

        $validated = $request->validate([
            'name' => 'required|unique:warehouses,warehouse_name|max:255',

        ]);
        
            $Warehouse->warehouse_name = $request->name;
            $Warehouse->save();
            return redirect()->route('warehouse.index')->with('message', 'New Warehouse saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['warehouse'] = $warehouse;
        return view('admin.inventoryViews.warehouse.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        if(Gate::denies('director-only')){
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'warehouse_name' => ['required', Rule::unique('warehouses' , 'warehouse_name')->ignore($warehouse)],
        ]);
        $warehouse->warehouse_name = $request->warehouse_name;

        $warehouse->save();
        return redirect()->route('warehouse.index')->with('message', 'Warehouse was updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        if(Gate::denies('director-only')){
            return redirect()->route('dashboard');
        }

        $warehouse->delete();
        return redirect()->route('warehouse.index');
    }
}
