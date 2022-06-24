<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Currency;
use App\Models\Import;
use App\Models\Provider;
use App\Models\SupplyItem;
use App\Models\SupplyOrder;

class SupplyOrderController extends Controller
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
        return view('backend.commandes.index')->with(array('providers' => $provider));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $supplies = SupplyItem::where('selected', 1)
            ->join('products', 'products.id', '=', 'supply_items.product_id')
            ->select('products.sku', 'products.title', 'products.photo', 'supply_items.qte', 'supply_items.selected', 'supply_items.id')
            ->orderBy('supply_items.id', 'DESC')
            ->paginate();

        $providers = Provider::all();
        $currencys = Currency::orderBy('id', 'DESC')->paginate();
        $vdata = ['supplies' => $supplies, 'providers' => $providers, 'currencys' => $currencys];
        $import = Import::latest()->first();
        if ($import && $import->status) {
            $vdata['status'] = $import->status;
        }
        $vdata['status'] = ($import && $import->status) ? $import->status : 'UNDEFINED';

        return view('backend.commandes.create', $vdata);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
