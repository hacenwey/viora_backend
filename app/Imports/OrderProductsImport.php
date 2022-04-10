<?php

namespace App\Imports;

use App\Models\OrderProduct;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderProductsImport implements ToModel, WithUpserts, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new OrderProduct([
            'id'    => $row['id'],
            'order_id'    => $row['order_id'],
            'product_id'    => Product::where('title', $row['product_name'])->first() != null ? Product::where('title', $row['product_name'])->first()->id : NULL,
            'product_name'    => $row['product_name'],
            'price'    => $row['price'],
            'quantity'    => $row['quantity'],
            'sub_total'    => $row['sub_total'],
            'attributes'    => $row['attributes'],
        ]);
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'id';
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
