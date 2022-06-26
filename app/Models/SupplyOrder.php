<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{
    use HasFactory;
    protected $table = 'supply-orders';
    protected $fillable = [
        'provider_id',
        'status',
        'arriving_time',
        'shipping_cost',
        'provider_expenses',
        'local_expenses'
    ];
}
