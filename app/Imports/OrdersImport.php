<?php

namespace App\Imports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class OrdersImport implements ToModel, WithUpserts, WithHeadingRow, WithChunkReading, ShouldQueue
{

    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Order([
            'id'    => $row['id'],
            'reference'    => $row['reference'],
            'user_id'    => $row['user_id'] ?? NULL,
            'sub_total'    => $row['sub_total'],
            'shipping_id'    => $row['shipping_id'] ?? NULL,
            'delivery_price'    => $row['delivery_price'],
            'coupon'    => $row['coupon'],
            'total_amount'    => $row['total_amount'],
            'payment_method'    => $row['payment_method'] ?? 'cod',
            'payment_status'    => $row['payment_status'],
            'status'    => $row['status'] ?? "Delivered",
            'first_name'    => $row['first_name'] ?? 'Client',
            'last_name'    => $row['last_name'] ?? "Client",
            'email'    => $row['email'] ?? NULL,
            'phone'    => $row['phone'] ?? "+22233767696",
            'country'    => $row['country'],
            'address1'    => $row['address1'],
            'created_at'    => $row['created_at'],
            'updated_at'    => $row['updated_at'],
            'town_city'    => $row['town_city'],
            'longitude'    => $row['longitude'] ?? NULL,
            'latitude'    => $row['latitude'] ?? NULL,
            'urgent'    => $row['urgent'] ?? 0,
            'notes'    => $row['notes'] ?? NULL,
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
