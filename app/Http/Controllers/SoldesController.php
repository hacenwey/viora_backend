<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solde;
use App\Models\Provider;
use App\Models\SupplyOrderItem;
class SoldesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        
        $transactions = Solde::join('supply_order_items','soldes.provider_id','=','supply_order_items.provider_id')->get();
        $providers = Provider::all();

        $vdata = ['transactions' => $transactions, 'providers' => $providers];
       

        return view('backend.soldes.index',$vdata);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.soldes.index');
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
            'somme' => 'required',
            'date' => 'required',
            'description' => 'required',
            'provider_id' => 'required',

            
        ]);
        $data = $request->all();
    
        $status = Solde::create($data);
        if ($status) {
            request()->session()->flash('success', 'transaction successfully created');
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('backend.soldes.index');
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
        //
    }
}
