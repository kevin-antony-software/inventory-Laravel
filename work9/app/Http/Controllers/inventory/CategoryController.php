<?php

namespace App\Http\Controllers\inventory;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\inventory\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
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
        $arr['categories'] = Category::all();
        return view('admin.inventoryViews.category.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('director-only')){
            return redirect()->route('home');
        }
        return view('admin.inventoryViews.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('director-only')){
            return redirect()->route('home');
        }
        $validatedData = $request->validate([
            'name' => 'required|unique:categories,name',
        ]);
            $Category = New Category;
            $Category->name = $request->name;
            $Category->save();
            return redirect()->route('category.index')->with('message', 'New Category saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['category'] = $category;
        return view('admin.inventoryViews.category.edit')->with($arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if(Gate::denies('director-only')){
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'category_name' => ['required', Rule::unique('categories' , 'name')],
        ]);
        $category->name = $request->category_name;

        $category->save();
        return redirect()->route('category.index')->with('message', 'Category was updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if(Gate::denies('director-only')){
            return redirect()->route('dashboard');
        }

        $category->delete();
        return redirect()->route('category.index');
    }
}
