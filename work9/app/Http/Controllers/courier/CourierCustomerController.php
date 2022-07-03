<?php

namespace App\Http\Controllers\courier;

use App\Http\Controllers\Controller;
use App\Models\courier\CourierCustomer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CourierCustomerController extends Controller
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
        $arr['CourierCustomers'] = CourierCustomer::orderBy('id', 'desc')->get();
        return view('admin.courier.customer.index')->with($arr);
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
        return view('admin.courier.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CourierCustomer $CourierCustomer)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'numeric',
            'mobile' => 'numeric',
        ]);

        $CourierCustomer->courier_customer_name = $request->customer_name;
        $CourierCustomer->address = $request->address;
        $CourierCustomer->mobile = $request->mobile;
        $CourierCustomer->phone = $request->phone;
        $CourierCustomer->save();
        return redirect()->route('CourierCustomer.index')->with('message', 'Customer was Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\courier\CourierCustomer  $courierCustomer
     * @return \Illuminate\Http\Response
     */
    public function show(CourierCustomer $courierCustomer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\courier\CourierCustomer  $courierCustomer
     * @return \Illuminate\Http\Response
     */
    public function edit(CourierCustomer $CourierCustomer)
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }
        $arr['CourierCustomer'] = $CourierCustomer;
        return view('admin.courier.customer.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\courier\CourierCustomer  $courierCustomer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourierCustomer $CourierCustomer)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string',
            'address' => 'required|string',
            'mobile' => 'numeric',
            'phone' => 'numeric',
        ]);
        $CourierCustomer->courier_customer_name = $request->customer_name;
        $CourierCustomer->address = $request->address;
        $CourierCustomer->mobile = $request->mobile;
        $CourierCustomer->phone = $request->phone;
        $CourierCustomer->save();
        return redirect()->route('CourierCustomer.index')->with('message', 'Customer was Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\courier\CourierCustomer  $courierCustomer
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourierCustomer $CourierCustomer)
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }
        $CourierCustomer->delete();
        return redirect()->route('CourierCustomer.index')->with('message', 'Customer Deleted');
    }
}
