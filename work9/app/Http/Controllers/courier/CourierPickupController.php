<?php

namespace App\Http\Controllers\courier;

use App\Http\Controllers\Controller;
use App\Models\courier\CourierCustomer;
use App\Models\courier\CourierPickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourierPickupController extends Controller
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
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }
        $arr['Courierpickups'] = CourierPickup::orderBy('id', 'desc')->get();
        return view('admin.courier.pickup.index')->with($arr);
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
        $arr['CourierCustomers'] = CourierCustomer::orderBy('id', 'desc')->get();
        return view('admin.courier.pickup.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CourierPickup $courierPickup)
    {
        $validatedData = $request->validate([
            'customer' => 'required|string',
        ]);
        if (DB::table('courier_customers')
            ->where('courier_customer_name', $request->customer)
            ->doesntExist()
        ) {
            return redirect()->route('CourierPickup.create')
                ->with('error', 'customer selected not available in the system, 
            create courier customer before scheduling a pickup!');
        }

        $customer = DB::table('courier_customers')
            ->where('courier_customer_name', $request->customer)
            ->first();
        $courierPickup->courier_customer_id = $customer->id;
        $courierPickup->courier_customer_name = $request->customer;
        $courierPickup->model = $request->model;
        $courierPickup->warranty = $request->warranty;
        $courierPickup->status = 'pending';
        $courierPickup->save();

        // send email to prompt
        $CN =  $customer->courier_customer_name;
        $CA = $customer->address;
        $CM = $customer->phone;

        $to = "customercare@promptxpress.lk";
        //$to = "retoplanka@gmail.com";
        $subject = "Repair Pick up ";
        $msg = "Repair machine to pick up \n FROM \n CUSTOMER NAME - "
            . $CN . " \n CUSTOMER ADDRESS - "
            . $CA . " \n CUSTOMER TEL - " . $CM .
            "\n\n TO \n K & K International Lanka Pvt Ltd \n No 9, 5th lane, Borupana Road, \n Ratmalana \n 0777696922 ";

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg, 70);

        $headers = "From: info@kandkinter.com" . "\r\n" .
            "CC: retoprepair@gmail.com, info@weld.lk";

        // send email
        mail($to, $subject, $msg, $headers);

        return redirect()->route('CourierPickup.index')->with('message', 'Pickup was Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\courier\CourierPickup  $courierPickup
     * @return \Illuminate\Http\Response
     */
    public function show(CourierPickup $courierPickup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\courier\CourierPickup  $courierPickup
     * @return \Illuminate\Http\Response
     */
    public function edit($courierPickupID)
    {
        $courierPickup = CourierPickup::where('id', $courierPickupID)->first();
        $customer = DB::table('courier_customers')
            ->where('courier_customer_name', $courierPickup->courier_customer_name)
            ->first();

        $CN = $customer->courier_customer_name;
        $CA = $customer->address;
        $CM = $customer->phone;

        $to = "customercare@promptxpress.lk";
        //$to = "retoplanka@gmail.com";
        $subject = "Repair Pick up ";
        $msg = "Repair machine to pick up not Picked up yet. THIS IS A KIND REMINDER TO PICKUP ASAP!! \n FROM \n CUSTOMER NAME - "
            . $CN . " \n CUSTOMER ADDRESS - "
            . $CA . " \n CUSTOMER TEL - " . $CM .
            "\n\n TO \n K & K International Lanka Pvt Ltd \n No 9, 5th lane, Borupana Road, \n Ratmalana \n 0777696922 ";

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg, 70);

        $headers = "From: info@kandkinter.com" . "\r\n" .
            "CC: retoprepair@gmail.com, info@weld.lk, ruwanp@promptxpress.lk";

        // send email
        mail($to, $subject, $msg, $headers);

        $arr['Courierpickups'] = CourierPickup::orderBy('id', 'desc')->get();
        return view('admin.courier.pickup.index')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\courier\CourierPickup  $courierPickup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourierPickup $courierPickup)
    {

        $cou = CourierPickup::where('id', $request->COPICKID)->first();
        $cou->status = "Received";
        $cou->save();
        return redirect()->route('CourierPickup.index')->with('message', 'Pickup was Received!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\courier\CourierPickup  $courierPickup
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourierPickup $courierPickup)
    {
        //
    }
}
