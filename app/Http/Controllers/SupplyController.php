<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrderItem;

class SupplyController extends Controller
{
    /**
     * Make a supply
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplies = SupplyOrderItem::whereNull('supply_order_id')->orderBy('id', 'DESC')->paginate();


        // get last import status
        // if it's not done or failed. hide everything and show him, traiement en cours.
        $vdata = ['supplies' => $supplies];
        return view('backend.srm.supply', $vdata);
    }




    /**
     * new supply
     */
    public function supply(Request $request)
    {

        //($request->duration, $request->file('journal'));

        $excel = collect([
            [
                'barcode' => 3337875546430,
                'qte' => 5
            ],
            [
                'barcode' => 3337875546413,
                'qte' => 5
            ]
        ]);




        $request->session()->flash('import', 'success');
        return back();
    }
}
