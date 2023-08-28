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
        if (is_null($row[1])) {
            return null;
        }
        $productData = [
            'title' => $row[2],
            'slug' => $row[3],
            'summary' => $row[4] ?? null,
            'description' => $row[5],
            'stock' => $row[6],
            'discount' => $row[7] ?? '0.00',
            'price' => $row[8] ?? '0.00',
            'photo' => $row[9] ?? "https://e-marsa.s3.us-east-2.amazonaws.com/product-placeholder.jpg",
            'status' => $row[10],
            'price_of_goods' => $row[11] ?? "0.00",

            'is_featured' => $row[14] ?? 0,
            'brand_id' => $row[15] ?? null,
            'free_shipping' => $row[19] ?? 0,
        ];

        $product = Product::updateOrCreate(
            ['sku' => $row[1]],
            $productData
        );

        return $product;
    }
}

            