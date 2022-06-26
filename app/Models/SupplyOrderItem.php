<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOrderItem extends Model
{
    use HasFactory;
    protected $table = 'supply_order_items';
    protected $fillable = ['qte', 'supply_item_id', 'selected' ,'product_id', 'supply_order_id', 'provider_id', 'purchase_price', 'currency_id', 'particular_exchange','provider_expenses','local_expenses'];
}
