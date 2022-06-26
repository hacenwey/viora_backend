<?php

namespace App\Jobs;

use App\Models\SupplyItem;
use App\Models\SupplyOrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use DB;
use Exception;

class OrderItemCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $provider_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($provider_id)
    {
        Log::info('Stating the order item check!');
        $this->provider_id = $provider_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info($this->provider_id);
        try {

            /*
            $toCreate = SupplyOrderItem::join('supply_items', 'supply_order_items.supply_order_id', '=', 'supply_items.id')
                ->whereRaw('supply_order_items.qte < supply_items.qte')
                ->where('supply_items.provider_id', $this->provider_id)
                ->select(DB::raw('(supply_items.qte - supply_order_items.qte) as qte'), 'supply_items.id', 'supply_items.product_id')
                ->get();

            DB::beginTransaction();
            //create new order items
            foreach($toCreate as $item) {
                SupplyOrderItem::create([
                    'product_id' => $item['product_id'],
                    'provider_id' => $item['provider_id'],
                    'qte' => $item['qte']
                ]);
            }
            DB::commit();
            Log::info(var_export($toCreate->toArray(), 1));

            */
        } catch (Exception $ex) {
            /*
            DB::rollback();
            Log::info($ex->getMessage());
            */
        }
    }
}
