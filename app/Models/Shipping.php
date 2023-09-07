<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;
class Shipping extends Model
{
    protected $fillable=['type','price', 'free_price', 'urgent_price','status'];



    public function cities()
    {
        return $this->belongsTo(City::class, 'shipping_id');
    }
}
