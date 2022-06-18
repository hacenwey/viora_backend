<?php

namespace App\Jobs;

use App\Imports\ImportJournalSupply;
use App\Models\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Maatwebsite\Excel\Excel;
use stdClass;

class SupplyProcessing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $import;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import)
    {
        Log::info('Stating the import' . var_export($import, 1));
        $this->import = $import;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $journal = [];
        Log::info('Stating the processing of the file');
        try {
            $journal = [];
            if (($open = fopen(storage_path('app/public/cdn/files/' . $this->import['file_name']), "r")) !== FALSE) {
                while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                    $journal[] = $data;
                }

                fclose($open);
            }
        } catch (Exception $ex) {
            Log::info('Error while processing supply file: ' . $ex->getMessage());
        }


        $invalid_products = [];
        $supply_order_items  = [];
        $data = array_shift($journal);

        foreach($data as $item) {
            $product = Product::firstWhere('sku', $item[0]);
            if(!$product) {
                $invalid_products [] = $item[0];
                continue;
            }

            $journal_qte = (int) $item[1];
            $duration = (int) $this->import['duration'];
            $journal_duration = (int) $this->import['journal_duration'];
            $qte = ($journal_qte * $duration) / $journal_duration;

            $supply_order_items [] = [
                'product_id' => $product->id,
                'qte' => $qte,
                'import_id' => $this->import['id'],
            ];
        }


        Log::info(var_export($supply_order_items, 1));
        Log::info(var_export($invalid_products, 1));

        //$csv = Excel::
    }
}
