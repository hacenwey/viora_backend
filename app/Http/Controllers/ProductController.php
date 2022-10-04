<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Imports\ProductsImport;
use App\Models\Category;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // $products = Product::orderBy('id', 'DESC')->paginate(10);
        $products = Product::query();
        if ($request->search) {
            $products = $products->where('sku', 'like', '%' . $request->search . '%')
                ->orWhere('id', 'like', '%' . $request->search . '%')
                ->orWhere('title', 'like', '%' . $request->search . '%')
                ->orWhere('slug', 'like', '%' . $request->search . '%')
                ->orWhere('status', 'like', '%' . $request->search . '%')
                ->orderBy('id', 'DESC')
                ->paginate(10);
        } else {
            $products = $products->orderBy('id', 'DESC')->paginate(10);
        }


        return view('backend.product.index', compact('products'));
    }

    public function import()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        (new ProductsImport)->queue(request()->file('file'), null, \Maatwebsite\Excel\Excel::XLSX);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // $data = Excel::toArray(new ProductsImport, request()->file('file'));

        // collect(head($data))
        //     ->each(function ($row, $key) {
        //         DB::table('products')
        //             ->where('sku', $row[0])
        //             ->update([
        //                 'stock' => $row[1]
        //             ]);
        //     });

        return redirect()->route('backend.product.index');
    }

    public function getProductsFiltered()
    {
        $products = Product::with(['categories'])->orderBy('id', 'desc');

        return Datatables::of($products)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = Brand::all();
        $categories = Category::where('is_parent', 1)->get();
        // return $category;
        return view('backend.product.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'sku' => 'required',
            'title' => 'string|required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'photo' => 'string|required',
            'brand_id' => 'required|exists:brands,id',
            'is_featured' => 'sometimes|in:1',
            'free_shipping' => 'sometimes|in:1',
            'categories' => 'array|required',
            'status' => 'required|in:active,inactive',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'discount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        $data = $request->all();
        $slug = Str::slug($request->title);
        $count = Product::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
        }
        $data['slug'] = $slug;
        $data['is_featured'] = $request->input('is_featured', 0);
        $data['free_shipping'] = $request->input('free_shipping', 0);

        $product = Product::create($data);
        if ($product) {
            $product->categories()->sync($request['categories']);
            request()->session()->flash('success', 'Product Successfully added');
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        return redirect()->route('backend.product.index');
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
    public function edit(Product $product)
    {
        $brands = Brand::get();
        $categories = Category::where('is_parent', 1)->get();
        $sub_categories = Category::where('is_parent', 0)->get();
        $product->load('categories', 'brand');

        return view('backend.product.edit', compact('product', 'categories', 'sub_categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // dd($request->all());
        $this->validate($request, [
            'sku' => 'required',
            'title' => 'string|required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'photo' => 'string|required',
            'brand_id' => 'required|exists:brands,id',
            'categories' => 'array|required',
            'status' => 'required|in:active,inactive',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'discount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        $data = $request->all();

        $data['is_featured'] = $request->has('is_featured');
        $data['free_shipping'] = $request->has('free_shipping');

        if ($request->stock != $product->stock) {
            $data['stock_last_update'] = Carbon::now();
        }

        // return $data;
        $status = $product->update($data);
        if ($status) {
            $product->categories()->sync($request['categories']);
            request()->session()->flash('success', 'Product Successfully updated');
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        $page = 1;
        if (request()->page != null) {
            $page = request()->page;
        }
        return redirect()->route('backend.product.index', ['page' => $page]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $status = $product->delete();

        if ($status) {
            request()->session()->flash('success', 'Product successfully deleted');
        } else {
            request()->session()->flash('error', 'Error while deleting product');
        }
        return redirect()->route('backend.product.index');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAll(Request $request)
    {

        $status = Product::whereIn("id", $request->ids)->delete();

        if ($status) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }




    // filter product py brands and products by categories
    public function filter(Request $request)

    {
      $name=$request->name;
        // $products = DB::table('products')
        // ->join('brands', 'brands.id', '=', 'products.brand_id')
        // ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
        // ->join('categories', 'category_id', '=', 'product_categories.category_id')->Where('brands.title', $request->name)->orWhere('categories.title', $request->name)->take(100)->get();
    //    $products =Product::with(["categories","brand","attributes"])->where('brand_id','!=',null)->Where('title', $request->name)->orWhere('title', $request->name)->take(100)->get();
        $products= Product::with(["brand"])->where('brand_id','!=',null);
        
        $products = $products->whereHas('brand', function($q) use($name)
            {
                $q->where('title', $name);
            })->get();
        $emptyproducts = $products->count() === 0;

       

        return response()->json([
            'title' => 'Most Popular',
            'enabled' => true,
            'items' => !$emptyproducts ? $products  : []
        ]);
    }



}

