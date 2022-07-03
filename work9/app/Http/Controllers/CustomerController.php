<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
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
        $arr['customers'] = Customer::orderBy('id', 'desc')->get();
        return view('admin.customer.index')->with($arr);
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

        $arr['users'] = User::all();
        return view('admin.customer.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'mobile' => 'required|numeric',
        ]);
        if ($request->customer_type == 'dealer'){
            $validatedData1 = $request->validate([
                'credit_limit' => 'required|numeric',
                'cheque_limit' => 'required|numeric',
                'owner_id' => 'required',
            ]);
        }

        $customer->customer_name = $request->customer_name;
        $customer-> customer_type = $request->customer_type;
        $customer->address = $request->address;
        $customer->mobile = $request->mobile;
        $customer->city = $request->city;
        $customer->VATnumber = $request->VATnumber;
        $customer->BRnumber = $request->BRnumber;
        $customer->email = $request->email;
        $customer->phone = $request->phone;

        if ($request->customer_type == 'dealer'){
            $customer->creditLimit = $request->credit_limit;
            $customer->chequeLimit = $request->cheque_limit;
            $customer->owner_ID = $request->owner_id;
            $ownerName = DB::table('users')->where('id' , $request->owner_id)->value('name');
            // dd($ownerName);
            $customer->owner_name = $ownerName;
        }

        $customer->save();
        return redirect()->route('customer.index')->with('message', 'Customer was Created!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        if (Gate::denies('store-keeper-only')) {
            return redirect()->route('dashboard');
        }
        $arr['customer'] = $customer;
        return view('admin.customer.show')->with($arr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
            $arr['customer'] = $customer;
            $arr['users'] = User::all();
            return view('admin.customer.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        if ($request->customer_type == 'dealer'){
            if (Gate::denies('director-only')) {
                return redirect()->route('dashboard');
            }
        } else {
            if (Gate::denies('managers-only')) {
                return redirect()->route('dashboard');
            }
        }
        $validatedData = $request->validate([
            'customer_name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'mobile' => 'required|numeric',
        ]);
        if ($request->customer_type == 'dealer'){
            $validatedData1 = $request->validate([
                'credit_limit' => 'required|numeric',
                'cheque_limit' => 'required|numeric',
                'owner_id' => 'required',
            ]);
        }

        $customer->customer_name = $request->customer_name;
        $customer-> customer_type = $request->customer_type;
        $customer->address = $request->address;
        $customer->mobile = $request->mobile;
        $customer->city = $request->city;
        $customer->VATnumber = $request->VATnumber;
        $customer->BRnumber = $request->BRnumber;
        $customer->email = $request->email;
        $customer->phone = $request->phone;

        if ($request->customer_type == 'dealer'){
            $customer->creditLimit = $request->credit_limit;
            $customer->chequeLimit = $request->cheque_limit;
            $customer->owner_ID = $request->owner_id;
            $ownerName = DB::table('users')->where('id' , $request->owner_id)->value('name');
            $customer->owner_name = $ownerName;
        }

        $customer->save();
        return redirect()->route('customer.index')->with('message', 'Customer was Updated!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }

            $customer->delete();
            return redirect()->route('customer.index')->with('message', 'Customer Deleted');

    }
}
