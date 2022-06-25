<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyItem;
use App\Models\Import;
use App\Models\Provider;
use App\Http\Services\UploadService;
use App\Jobs\SupplyProcessing;
use App\Models\SupplyOrderItem;
use Exception;
use Log;
use DB;

class SupplyController extends Controller
{
    /**
     * Make a supply
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplies = SupplyItem::whereDoesntHave('items')
            ->join('products', 'products.id', '=', 'supply_items.product_id')
            ->select('products.sku', 'products.title', 'products.photo', 'supply_items.qte', 'supply_items.selected', 'supply_items.id', 'supply_items.provider_id')
            ->orderBy('supply_items.id', 'DESC')
            ->paginate();

        $providers = Provider::all();
        $vdata = ['supplies' => $supplies, 'providers' => $providers];
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

        if ($import) {
            Log::info('L\'import a été ajouter dans la base avec succes');
            dispatch(new SupplyProcessing($import->toArray()));
        }

        // Ajouter le status de l'import
        request()->session()->flash('import', $import ? 'success' : 'error');
        return back();
    }


    public function update(Request $request, $id)
    {
        $supplyItem = SupplyItem::find($id);
        $this->validate($request, [
            'qte' => '',
            'provider_id' => '',
            'selected' => '',
        ]);
        $data = $request->all();

        $supplyItem->update($data);

        return response(['message' => 'successfully modifed !', 'data' => $supplyItem]);
    }


    public function supplyOrderItemUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'qte' => '',
            'purchase_price' => '',
            'selected' => '',
            'supply_item_id' => '',
            'currency_id' => '',
            'provider_id' => '',
            'product_id' => '',
            'particular_exchange' => '',
        ]);
        $data = $request->all();

        try {
            $supplyItem = SupplyOrderItem::find($id);
            $supplyItem->update($data);
        } catch (Exception $ex) {
        }


        return response(['message' => 'successfully modifed !', 'data' => $supplyItem]);
    }


    /**
     * Permet de pereparer une commande
     */
    public function preOrder()
    {
        return 'Hello world';
    }


    public function confirm()
    {
        $supplyItems = SupplyItem::whereDoesntHave('items')->where('selected', 1)->get();
        try {
            DB::beginTransaction();
            foreach ($supplyItems as $sitem) {
                SupplyOrderItem::create([
                    'qte' => $sitem['qte'],
                    'supply_item_id' => $sitem['id'],
                    'provider_id' => $sitem['provider_id'],
                    'product_id' => $sitem['product_id'],
                ]);
            }
            DB::commit();
        } catch (Exception $ex) {
            Log::info('an error was occured during order items creation');
            Log::info($e->getMessage());
            DB::rollback();
        }
        return $supplyItems;
    }
}
