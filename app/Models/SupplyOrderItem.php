<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOrderItem extends Model
{
    use HasFactory;
    protected $table = 'supply_order_items';
    protected $fillable = ['qte','import_id','product_id','supply_order_id'];

}
