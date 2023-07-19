<?php

namespace App\Imports;

use App\Models\Product;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithUpserts, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'id' => $row['id'],
            'sku' => $row['sku'] ?? $row["id"],
            'title' => $row['title'],
            'slug' => Str::random(40),
            'summary' => $row['summary'] ?? NULL,
            'description' => $row['description'],
            'stock' => $row['stock'],
            'discount' => $row['discount'] ?? '0.00',
            'price' => $row['price'] ?? '0.00',
            'photo' => $row['photo'] ?? "https://e-marsa.s3.us-east-2.amazonaws.com/product-placeholder.jpg",
            'status' => $row['status'],
            'price_of_goods' => $row['price_of_goods'] ?? "0.00",
            'discount_start' => NULL,
            'discount_end' => NULL,
            'is_featured' => $row['is_featured'] ?? 0,
            'brand_id' => $row['brand_id'] ?? NULL,
            'created_at' => $row['created_at'] ?? NULL,
            'updated_at' => $row['updated_at'] ?? NULL,
            'deleted_at' => $row['deleted_at'] ?? NULL,
            'free_shipping' => $row['free_shipping'] ?? 0,
            'return_in_stock' => $row['return_in_stock'] ?? Null,
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
        return 10;
    }
}
