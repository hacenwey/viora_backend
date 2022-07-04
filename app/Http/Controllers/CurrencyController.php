<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use Exception;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencys = Currency::orderBy('id', 'DESC')->paginate();
        return view('backend.currencys.index')->with('currencys', $currencys);
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
            'name' => 'string|required',
            'code' => 'string|required',
            'exchange_rate' => 'string|required',
        ]);
        $status = Currency::create($request->all());
        if ($status) {
            request()->session()->flash('success', 'Currency  successfully created');
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('backend.currencys.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tenant\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tenant\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenant\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string',
            'code' => 'string',
            'exchange_rate' => 'string',
        ]);

        try {
            $status = Currency::find($id)->update($request->all());
            if ($status) {
                return response(['message' => 'success']);
            } else {
                return response(['message' => 'error'], 400);
            }
        } catch (Exception $ex) {
            return response(['message' => 'error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tenant\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currency = Currency::find($id);
        if ($currency) {
            $status = $currency->delete();
            if ($status) {
                request()->session()->flash('success', 'Currency successfully deleted');
            } else {
                request()->session()->flash('error', 'Error, Please try again');
            }
            return redirect()->route('backend.currencys.index');
        } else {
            request()->session()->flash('error', 'Currency not found');
            return redirect()->back();
        }
    }
}
