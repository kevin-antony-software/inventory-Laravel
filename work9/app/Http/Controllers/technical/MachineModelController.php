<?php

namespace App\Http\Controllers\technical;
use App\Http\Controllers\Controller;

use App\Models\technical\MachineModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class MachineModelController extends Controller
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
        $arr['models'] = MachineModel::orderBy('id', 'desc')->get();
        return view('admin.technical.models.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('home');
        }
        return view('admin.technical.models.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MachineModel $model)
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('home');
        }
        $validatedData = $request->validate([
            'model_name' => 'required|unique:machine_models,name',
            'category_name' => 'required',
        ]);
        $model->name = $request->model_name;
        $model->sizeValue = $request->category_name;
        $model->save();
        return redirect()->route('machineModel.index')->with('message', 'new Models created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function show(MachineModel $component)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function edit(MachineModel $component)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MachineModel $component)
    {
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function destroy(MachineModel $machineModel)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $machineModel->delete();
        return redirect()->route('machineModel.index')->with('message', 'Model deleted');
    }
}

