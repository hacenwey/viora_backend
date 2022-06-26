<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Currency;
use App\Models\Import;
use App\Models\Provider;
use App\Models\SupplyItem;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;

class SupplyOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = SupplyOrder::where('status', '!=', 'ARCHIVED')
            ->join('providers', 'providers.id', 'supply-orders.provider_id')
            ->select('supply-orders.status', 'supply-orders.arriving_time', 'supply-orders.created_at', 'supply-orders.id', 'providers.name as provider_name')
            ->orderBy('supply-orders.id', 'DESC')
            ->paginate();
        return view('backend.commandes.index')->with(['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {

        $pid = (int) $req->provider_id;
        $order_items = SupplyOrderItem::whereNull('supply_order_id')
            ->join('products', 'products.id', '=', 'supply_order_items.product_id')
            ->select('products.sku', 'products.title', 'products.photo', 'supply_order_items.qte', 'supply_order_items.selected', 'supply_order_items.id', 'supply_order_items.purchase_price', 'supply_order_items.currency_id', 'supply_order_items.particular_exchange')
            ->orderBy('supply_order_items.id', 'DESC')
            ->where('supply_order_items.provider_id', $pid)
            ->paginate();
        $isEdit = false;
        $providers = Provider::all();
        $currencys = Currency::orderBy('id', 'DESC')->paginate();
        $vdata = [
            'order_items' => $order_items,
            'providers' => $providers,
            'currencys' => $currencys,
            'provider_id' => $pid,
            'isEdit' => $isEdit,
            'orderID' => NULL,
            'order' => NULL
        ];

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
    public function edit(Request $req, $id)
    {
        $pid = (int) $req->provider_id;
        $order_items = SupplyOrderItem::join('products', 'products.id', '=', 'supply_order_items.product_id')
            ->select('products.sku', 'products.title', 'products.photo', 'supply_order_items.qte', 'supply_order_items.selected', 'supply_order_items.id', 'supply_order_items.purchase_price', 'supply_order_items.currency_id', 'supply_order_items.particular_exchange')
            ->orderBy('supply_order_items.id', 'DESC')
            ->where('supply_order_id', $id)
            ->paginate();
        # TODO grou requests
        $order = SupplyOrder::find($id);
        $isEdit = true;
        $providers = Provider::all();
        $currencys = Currency::orderBy('id', 'DESC')->paginate();
        $vdata = [
            'order_items' => $order_items,
            'providers' => $providers,
            'currencys' => $currencys,
            'provider_id' => $pid,
            'isEdit' => $isEdit,
            'orderID' => $id,
            'order' => $order
        ];

        return view('backend.commandes.create', $vdata);
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
