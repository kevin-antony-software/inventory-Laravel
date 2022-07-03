<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ChequeReportController extends Controller
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
        return view('admin.reports.chequeReport.index');
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
        if($request->status == "Passed"){
            $arr['cheques'] = DB::table('cheques')->where('status', 'passed')->get();
            return view('admin.reports.chequeReport.show')->with($arr);
        } elseif ($request->status == "Returned"){
            $arr['cheques'] = DB::table('cheques')->where('status', 'returned')->get();
            return view('admin.reports.chequeReport.show')->with($arr);
        }elseif ($request->status == "Pending"){
            $arr['cheques'] = DB::table('cheques')->where('status', 'pending')->get();
            return view('admin.reports.chequeReport.show')->with($arr);
        } elseif ($request->status == "All"){
            $arr['cheques'] = DB::table('cheques')->all();
            return view('admin.reports.chequeReport.show')->with($arr);
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
