<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\Currency;

class FournisseursController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $provider = Provider::with('currency')->orderBy('id', 'DESC')->paginate();
        $currencys = Currency::orderBy('id', 'DESC')->paginate();
        return view('backend.providers.fournisseurs')->with(array('providers'=>$provider, 'currencys' => $currencys));
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
        $this->validate($request, [
            'name' => 'string|required',
            'email' => 'string|required',
            'phone' => 'string|required',
            'currency_id' => 'required',
        ]);
        $status = Provider::create($request->all());
        if ($status) {
            request()->session()->flash('success', 'Provider successfully created');
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('backend.provider.index');
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
    public function edit(Request $request,$id)
    {

        $this->validate($request, [
            'name' => 'string|required',
            'email' => 'string|required',
            'phone' => 'string|required',
            'currency_id' => 'required',
        ]);
        $provider = Provider::find($id);
        $status = $provider->update($request->all());
        if ($status) {
            request()->session()->flash('success', 'Provider updated created');
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('backend.provider.index');
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
        dd('1');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $povider = Provider::findOrFail($id);
        $status = $povider->delete();
        if ($status) {
            request()->session()->flash('success', 'povider successfully deleted');
        } else {
            request()->session()->flash('error', 'Error occurred while deleting banner');
        }
        return redirect()->route('backend.provider.index');
    }
}
