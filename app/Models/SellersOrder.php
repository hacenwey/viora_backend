<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class SellersOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'reference',
        'seller_id',
        'order_id',
        'status',
    ];

    public function sellersOrderProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\SellersOrderProduct', 'sellers_order_id', 'id');
    }
  
        public function seller()
        {
            return $this->belongsTo(User::class, 'seller_id','id');
        }

        public function order(){
            return $this->belongsTo('App\Models\Order');
        }
         
         
    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\SellersOrderProduct','sellers_order_id','id');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->reference = 'SA-' . strtoupper(uniqid());
        });
    }
}
