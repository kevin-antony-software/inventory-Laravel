<?php

namespace App\Http\Controllers\courier;

use App\Http\Controllers\Controller;
use App\Models\courier\CourierCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class CourierPackingController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }
        $arr['customers'] = CourierCustomer::all();
        return view('admin.courier.packing.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        for ($x = 1; $x <= 20; $x++) {
            $name = 'customer' . $x;
            $qty = 'Qty' . $x;
            if ($request->$name != "") {
                if (DB::table('courier_customers')->where('courier_customer_name', $request->$name)->doesntExist()) {
                    return redirect()->route('CourierPacking.create')->with('error', 'customer doesnt exist')->withInput();
                }
                if ($request->$qty == "") {
                    return redirect()->route('CourierPacking.create')->with('error', 'qty cant be empty')->withInput();
                }
            }
        }

        $customerList = array();
        for ($x = 1; $x <= 20; $x++) {
            $name = 'customer' . $x;
            $qty = 'Qty' . $x;
            if ($request->$name != "") {
                $customer = CourierCustomer::where('courier_customer_name', $request->$name)->first();

                for ($h = 0; $h < $request->$qty; $h++)
                    array_push($customerList, $customer);
            }
        }
        $arr['customerList'] = $customerList;
        $pdf = PDF::loadView('admin.courier.packing.print', $arr);
        return $pdf->download('packingList.pdf');
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
