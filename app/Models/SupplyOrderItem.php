<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOrderItem extends Model
{
    use HasFactory;
    protected $table = 'supply_order_items';
    protected $fillable = ['qte', 'supply_item_id', 'selected' ,'product_id', 'supply_order_id', 'provider_id', 'purchase_price', 'currency_id', 'particular_exchange'];


    /**
     * Get the user that owns the SupplyOrderItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplyItem()
    {
        return $this->belongsTo(SupplyItem::class, 'supply_order_id');
    }
}
