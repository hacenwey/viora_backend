<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyOrderItem;
use App\Models\Import;
use App\Http\Services\UploadService;
use App\Jobs\UploadTraitement;
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
         $status = false;
        $import = Import::latest()->first();
          if($import->status=='progress'){
            $status = true;
          }
        $vdata = ['supplies' => $supplies,'status' => $status];
        return view('backend.srm.supply', $vdata);
    }




    /**
     * new supply
     */
    public function supply(Request $request)
    {

        $data = $request->all();
        UploadService::uploadFile($request->file('journal'),$data,'file_name');
        $status = Import::create($data);
        dispatch(new UploadTraitement());
        if ($status) {
            request()->session()->flash('import', 'success');
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return back();
    }

}
