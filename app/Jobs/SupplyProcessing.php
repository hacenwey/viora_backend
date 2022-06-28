<?php

namespace App\Jobs;

use App\Models\Import;
use App\Models\Product;
use App\Models\SupplyItem;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use DB;
use Illuminate\Support\Str;

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
            $open = fopen(storage_path('app/public/cdn/files/' . $this->import['file_name']), "r");
            if ($open !== FALSE) {
                $delimiter = $this->detectDelimiter();
                while (($data = fgetcsv($open, 1000, $delimiter)) !== FALSE) {
                    $journal[] = $data;
                }

                fclose($open);
            }
        } catch (Exception $ex) {
            Import::where('id', $this->import['id'])->update(['status' => 'FAILED']);
            Log::info('Error while processing supply file: ' . $ex->getMessage());
        }


        $invalid_products = [];
        $supply_order_items  = [];
        array_shift($journal);

        foreach ($journal as $item) {
            $product = Product::firstWhere('sku', $item[0]);
            if (!$product) {
                $product = Product::create([
                    'sku' => $item[0],
                    'title' => $item[1],
                    'slug' => Str::random(25),
                    'summary' => $item[1],
                    'photo' => '-',
                    'price' => 1
                ]);
                $invalid_products[] = $item[0];
            }

            $journal_qte = (int) $item[2];
            $duration = (int) $this->import['duration'];
            $journal_duration = (int) $this->import['journal_duration'];
            $qte = (int) (($journal_qte * $duration) / $journal_duration);

            if ($qte > 0) {
                $supply_order_items[] = [
                    'product_id' => $product->id,
                    'qte' => $qte,
                    'import_id' => $this->import['id'],
                ];
            }
        }

        try {
            DB::beginTransaction();
            $failed_skus = json_encode($invalid_products);

            if (count($supply_order_items) > 0) {
                SupplyItem::insert($supply_order_items);
            }

            Import::where('id', $this->import['id'])->update([
                'status' => 'DONE',
                'failed_skus' => $failed_skus
            ]);

            Log::info('DONE!');
            DB::commit();
        } catch (Exception $ex) {
            Import::where('id', $this->import['id'])->update(['status' => 'FAILED']);
            Log::info('Error while finishing the process' . $ex->getMessage());
            DB::rollback();
        }
    }


    /**
     * @param string $csvFile Path to the CSV file
     * @return string Delimiter
     */
    public function detectDelimiter()
    {
        $handle = fopen(storage_path('app/public/cdn/files/' . $this->import['file_name']), "r");
        $delimiters = [";" => 0, "," => 0, "\t" => 0, "|" => 0];

        $firstLine = fgets($handle);
        fclose($handle);
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }
}
