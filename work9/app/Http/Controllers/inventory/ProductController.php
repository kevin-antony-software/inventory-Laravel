<?php

namespace App\Http\Controllers\inventory;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\inventory\Category;
use App\Models\inventory\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
        $arr['products'] = Product::All();
        return view('admin.inventoryViews.product.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['categories'] = DB::table('categories')->get();
        return view('admin.inventoryViews.product.create')->with($arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            
            'name' => 'required|unique:products,product_name',
            'category_id' => 'required',
            'usd' => 'required|numeric',
            'usdRate' => 'required|numeric',
            'DFP' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $Product = New Product();
        $Product->product_name = $request->name;
        $Product->category_id = $request->category_id;
        $Product->category_name = DB::table('categories')->where('id', $request->category_id)->value('name');
        $Product->USDcost = $request->usd;
        $Product->ExchangeUSDrate = $request->usdRate;
        $Product->firstCost = $request->usd * $request->usdRate;
        $Product->DFP = $request->DFP;
        $Product->totalcost = $request->usd * $request->usdRate * (1 + ($request->DFP)/100);
        $Product->price = $request->price;

        $Product->save();
        return redirect()->route('product.index')->with('message', 'New Product saved!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $arr['categories'] = Category::all();
        $arr['product'] = $product;
        return view('admin.inventoryViews.product.edit')->with($arr);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $validatedData = $request->validate([
            'product_name' => ['required', Rule::unique('products', 'product_name')->ignore($product->id)],
            'usd' => 'required|numeric',
            'usdRate' => 'required|numeric',
            'DFP' => 'required|numeric',
            'price' => 'required|numeric',
        ]);
     
        $product->product_name = $request->product_name;
        $product->category_id = $request->category_id;
        $product->category_name = DB::table('categories')->where('id', $request->category_id)->value('name');
        $product->USDcost = $request->usd;
        $product->ExchangeUSDrate = $request->usdRate;
        $product->firstCost = $request->usd * $request->usdRate;
        $product->DFP = $request->DFP;
        $product->totalcost = $request->usd * $request->usdRate * (1 + ($request->DFP)/100);
        $product->price = $request->price;

        $product->save();
        return redirect()->route('product.index')->with('message', 'Product Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (Gate::denies('director-only')) {
            return redirect()->route('dashboard');
        }
        $qty = DB::table('inventories')
            ->where('product_id', $product->id)
            ->sum('qty');
        if($qty > 0){
            return redirect()->route('product.index')->with('message', 'have stocks');
        } else {
            $product->delete();
            return redirect()->route('product.index')->with('message', 'product deleted');
        }

    }
}
