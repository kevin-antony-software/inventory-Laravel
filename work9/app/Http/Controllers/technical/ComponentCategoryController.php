<?php

namespace App\Http\Controllers\technical;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\technical\ComponentCategory;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationData;

class ComponentCategoryController extends Controller
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
        $arr['ComponentCategorys'] = ComponentCategory::orderBy('id', 'desc')->get();
        return view('admin.technical.categories.index')->with($arr);  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('managers-only')){
            return redirect()->route('dashboard');
        }
        return view('admin.technical.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ComponentCategory $category)
    {
        if(Gate::denies('managers-only')){
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'category_name' => 'required|unique:component_categories,name',
        ]);
        $category->name = $request->category_name;
        $category->save();
        return redirect()->route('ComponentCategory.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(ComponentCategory $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(ComponentCategory $ComponentCategory)
    {
        if (Gate::denies('managers-only')) {
            return redirect()->route('dashboard');
        }

        $arr['ComponentCategory'] = $ComponentCategory;
        return view('admin.technical.categories.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ComponentCategory $ComponentCategory)
    {
        if(Gate::denies('managers-only')){
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'category_name' => ['required', Rule::unique('component_categories' , 'name')],
        ]);
        $ComponentCategory->name = $request->category_name;

        $ComponentCategory->save();
        return redirect()->route('ComponentCategory.index')->with('message', 'Category was updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComponentCategory $ComponentCategory)
    {
        if(Gate::denies('managers-only')){
            return redirect()->route('dashboard');
        }

        $ComponentCategory->delete();
        return redirect()->route('ComponentCategory.index');
    }
}
