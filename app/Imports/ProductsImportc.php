<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;

class ProductsImportc implements ToModel
{
    private $isFirstRow;

    public function __construct()
    {
        $this->isFirstRow = true;
    }

    public function model(array $row)
    {
        if ($this->isFirstRow) {
            $this->isFirstRow = false;
            return null;
        }

        Log::info('Row Data: ' . json_encode($row));

        $product = new Product([
            'id' => $row[0],
            'sku' => $row[1] ?? $row[0],
            'title' => $row[2],
            'slug' => Str::random(40),
            'summary' => $row[3] ?? null,
            'description' => $row[4],
            'stock' => $row[5],
            'discount' => $row[6] ?? '0.00',
            'price' => $row[7] ?? '0.00',
            'photo' => $row[8] ?? "https://e-marsa.s3.us-east-2.amazonaws.com/product-placeholder.jpg",
            'status' => $row[9],
            'price_of_goods' => $row[10] ?? "0.00",
            'is_featured' => $row[11] ?? 0,
            'brand_id' => $row[12] ?? null,
            'free_shipping' => $row[13] ?? 0,
            'return_in_stock' => $row[14] ?? null,
        ]);

        return $product;
    }
}

            