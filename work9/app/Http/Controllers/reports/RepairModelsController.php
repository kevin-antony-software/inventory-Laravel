<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use App\Models\technical\Issue;
use App\Models\technical\Job;
use App\Models\technical\MachineModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class RepairModelsController extends Controller
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
        $arr['models'] = MachineModel::orderBy('name', 'asc')->get();
        $arr['issues'] = Issue::orderBy('issue', 'asc')->get();
        return view('admin.reports.repairModelsReport.index')->with($arr);
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
        if($request->Model != ""){
            $arr['items'] = DB::table('jobs')
            ->select(DB::raw('COUNT(id) as total, MONTH(created_at) as month, YEAR(created_at) as year'))
            ->where('model', $request->Model)
            ->groupBy(DB::raw('YEAR(created_at) ASC, MONTH(created_at) ASC'))
            ->get();
        } else if ($request->Issue != "") {
            $arr['items'] = DB::table('jobs')
            ->select(DB::raw('COUNT(id) as total, MONTH(created_at) as month, YEAR(created_at) as year'))
            ->where('issue', $request->Issue)
            ->groupBy(DB::raw('YEAR(created_at) ASC, MONTH(created_at) ASC'))
            ->get();
        }
        return view('admin.reports.repairModelsReport.show')->with($arr);
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
