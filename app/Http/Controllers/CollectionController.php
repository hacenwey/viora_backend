<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $collections = Collection::orderBy('id', 'DESC')->paginate(10);
        return view('backend.collections.index')->with('collections', $collections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $products = Product::where('status', 'active')->where('stock', '!=', 0)->get();
        $categories = Category::where('status', 'active')->get();
        return view('backend.collections.create', compact('products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'string|required|max:50',
            'summary' => 'string|nullable',
            'description' => 'string|nullable',
            'photo' => 'string|required',
            'type' => 'required|in:product,category',
            'status' => 'required|in:active,inactive',
        ]);

        $collection = Collection::create($request->all());

        if($collection->type == 'product'){
            $collection->products()->sync($request['products']);
        } else if($collection->type == 'category'){
            $collection->categories()->sync($request['categories']);
        }

        if ($collection) {
            request()->session()->flash('success', 'Collection successfully added');
        } else {
            request()->session()->flash('error', 'Error occurred while adding collection');
        }
        return redirect()->route('backend.collections.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenant\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Collection $collection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenant\Collection  $collection
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Collection $collection)
    {
        $products = Product::where('status', 'active')->where('stock', '!=', 0)->get();
        $categories = Category::where('status', 'active')->get();

        $collection->load('categories', 'products');
//        dd($collection->products->pluck('id')->toArray());
        return view('backend.collections.edit', compact('products', 'categories', 'collection'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenant\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {
        $this->validate($request, [
            'title' => 'string|required|max:50',
            'summary' => 'string|nullable',
            'description' => 'string|nullable',
            'photo' => 'string|required',
            'type' => 'required|in:product,category',
            'status' => 'required|in:active,inactive',
        ]);

        $collection->update($request->all());

        if($collection->type == 'product'){
            $collection->products()->sync($request['products']);
        } else if($collection->type == 'category'){
            $collection->categories()->sync($request['categories']);
        }

        if ($collection) {
            request()->session()->flash('success', 'Collection successfully updated');
        } else {
            request()->session()->flash('error', 'Error occurred while adding collection');
        }
        return redirect()->route('backend.collections.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenant\Collection  $collection
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Collection $collection)
    {
        $status = $collection->delete();
        if ($status) {
            request()->session()->flash('success', 'Collection successfully deleted');
        } else {
            request()->session()->flash('error', 'Error occurred while deleting collection');
        }
        return redirect()->route('backend.collections.index');
    }
}
