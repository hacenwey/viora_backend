<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductSupplier;
use App\Models\Provider;
use App\Models\Product;
class ProductSuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productsSuppliers = ProductSupplier::with('provider', 'product')->orderBy('id', 'DESC')->paginate();
        $providers = Provider::orderBy('id', 'DESC')->paginate();
        $products = Product::orderBy('id', 'DESC')->paginate();

        return view('backend.ProductsSuppliers.index')->with(['productsSuppliers'=> $productsSuppliers,'providers'=>$providers,'products'=>$products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'provider_id' => 'required',
            'product_id' => 'required',
            
        ]);
        $data = $request->all();
    
        $status = ProductSupplier::create($data);
        if ($status) {
            request()->session()->flash('success', 'Product Supplier successfully created');
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('backend.productsSuppliers.index');
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
        $ProductSupplier = ProductSupplier::find($id);
        if ($ProductSupplier) {
            $status = $ProductSupplier->delete();
            if ($status) {
                request()->session()->flash('success', 'Product Supplier successfully deleted');
            } else {
                request()->session()->flash('error', 'Error, Please try again');
            }
            return redirect()->route('backend.productsSuppliers.index');
        } else {
            request()->session()->flash('error', 'Brand not found');
            return redirect()->back();
        }
    }
    }

