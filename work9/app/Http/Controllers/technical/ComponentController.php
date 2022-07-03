<?php

namespace App\Http\Controllers\technical;

use App\Http\Controllers\Controller;
use App\Models\inventory\Category;
use App\Models\technical\Component;
use App\Models\technical\ComponentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ComponentController extends Controller
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
        $arr['components'] = Component::orderBy('id', 'desc')->get();
        return view('admin.technical.components.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arr['categories'] = ComponentCategory::orderBy('name', 'asc')->get();
        return view('admin.technical.components.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Component $component)
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'component_name' => 'required|unique:components,component_name',
            'category_id' => 'required',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
        ]);
        $component->component_name = $request->component_name;
        $component->category_id = $request->category_id;
        $component->category_name = DB::table('component_categories')->where('id', $request->category_id)->value('name');
        $component->cost = $request->cost;
        $component->price = $request->price;
        $component->save();
        return redirect()->route('component.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function show(Component $component)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function edit(Component $component)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['categories'] = ComponentCategory::orderBy('name', 'asc')->get();
        $arr['component'] = $component;
        return view('admin.technical.components.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Component $component)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'category_id' => 'required',
            'component_name' => ['required', Rule::unique('components', 'component_name')->ignore($component->id)],
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
        ]);
        $component->component_name = $request->component_name;
        $component->category_id = $request->category_id;
        $component->category_name = DB::table('component_categories')->where('id', $request->category_id)->value('name');
        $component->cost = $request->cost;
        $component->price = $request->price;
        $component->save();

        return redirect()->route('component.index')->with('message', 'Component was updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function destroy(Component $component)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $component->delete();
        return redirect()->route('component.index')->with('message', 'component deleted');
    }
}
