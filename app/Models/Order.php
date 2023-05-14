<?php

namespace App\Models;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\Model;

class Order extends Model implements Searchable
{
    protected $fillable=[
        'id',
        'user_id',
        'reference',
        'delivery_charge',
        'status',
        'total_amount',
        'sub_total',
        'first_name',
        'last_name',
        'country',
        'city',
        'address1',
        'town_city',
        'phone',
        'email',
        'payment_method',
        'payment_status',
        'shipping_id',
        'coupon',
        'urgent',
        'longitude',
        'latitude',
        'notes',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $random_number = rand(1000, 9999);
            $next_reference = str_pad(Order::all()->max('reference') + 1, 6, 0, STR_PAD_LEFT);
            $reference = 'OR-' . $random_number . '-' . $next_reference;
            
            $model->reference = $reference;        
        });
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\OrderProduct','order_id','id');
    }

    public function cart_info(){
        return $this->hasMany('App\Models\Cart','order_id','id');
    }
    public static function getAllOrder($id){
        return Order::with('cart_info')->find($id);
    }
    public static function countActiveOrder(){
        $data=Order::count();
        if($data){
            return $data;
        }
        return 0;
    }
    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function shipping(){
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getSearchResult(): SearchResult
     {
        $url = route('admin.order.show', $this->id);

         return new \Spatie\Searchable\SearchResult(
            $this,
            $this->reference,
            $url
         );
     }

}
