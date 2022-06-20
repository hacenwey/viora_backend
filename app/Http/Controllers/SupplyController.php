<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrderItem;
use App\Models\Import;
use App\Models\Provider;
use App\Http\Services\UploadService;
use App\Jobs\SupplyProcessing;
use Exception;
use Log;

class SupplyController extends Controller
{
    /**
     * Make a supply
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplies = SupplyOrderItem::whereNull('supply_order_id')
        ->join('products', 'products.id', '=', 'supply_order_items.product_id')
        ->select('products.sku', 'products.title', 'supply_order_items.qte','supply_order_items.selected','supply_order_items.id')
        ->orderBy('supply_order_items.id', 'DESC')
        ->paginate();
        $provider = Provider::all();
        $vdata = ['supplies' => $supplies,'providers' => $provider];
        $import = Import::latest()->first();
        if ($import && $import->status) {
            $vdata['status'] = $import->status;
        }
        $vdata['status'] = ($import && $import->status) ? $import->status : 'UNDEFINED';

        return view('backend.srm.supply', $vdata);
    }




    /**
     * new supply
     */
    public function supply(Request $request)
    {
        $import = false;
        $payload = $request->all();
        try {
            UploadService::uploadFile($request->file('journal'), $payload, 'file_name');
            $import = Import::create($payload);
        } catch (Exception $e) {
            request()->session()->flash('import_error', 'Une erreur est survenu lors de l\'import, merci de vérifier votre fichier excel');
            Log::info('An error was occured while: ' . $e->getMessage());
        }

        if($import) {
            Log::info('L\'import a été ajouter dans la base avec succes');
            dispatch(new SupplyProcessing($import->toArray()));
        }

        // Ajouter le status de l'import
        request()->session()->flash('import', $import ? 'success' : 'error');
        return back();
    }


    public function update(Request $request, $id){
        $supplyOrderItem = SupplyOrderItem::find($id);
        $this->validate($request, [
            'qte' => 'required',
            'provider_id' => 'required',
            'selected'=> 'required',
        ]);
        $data = $request->all();

        $supplyOrderItem->fill($data)->save();

        return back();

    }
}
