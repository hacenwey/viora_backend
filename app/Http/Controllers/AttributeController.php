<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attribute = Attribute::orderBy('id', 'DESC')->paginate('10');
        return view('backend.attribute.index')->with('attributes', $attribute);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.attribute.create');
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
            'code' => 'string|required',
            'name' => 'string|required',
            'frontend_type' => 'required|in:select,radio,text,text_area'
        ]);

        $status = Attribute::create($request->all());
        if ($status) {
            request()->session()->flash('success', 'Attribute Successfully added');
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        return redirect()->route('backend.attribute.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute)
    {
        return view('backend.attribute.edit')->with('attribute', $attribute);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        $this->validate($request, [
            'code' => 'string|required',
            'name' => 'string|required',
            'frontend_type' => 'required|in:select,radio,text,text_area'
        ]);

        $status = $attribute->update($request->all());
        if ($status) {
            request()->session()->flash('success', 'Attribute Successfully updated');
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        return redirect()->route('backend.attribute.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        $status = $attribute->delete();

        if ($status) {
            request()->session()->flash('success', 'Attribute successfully deleted');
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('backend.attributes.index');
    }
}
