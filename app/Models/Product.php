<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use App\Models\Cart;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Product extends Model implements Searchable
{

    protected $with = ['categories', 'attributes'];
    protected $appends = ['has_active_discount', 'CartQuantity', 'image'];

    protected $fillable = [
        'id',
        'sku',
        'title',
        'slug',
        'summary',
        'description',
        'price',
        'price_of_goods',
        'brand_id',
        'discount',
        'discount_start',
        'discount_end',
        'status',
        'photo',
        'stock',
        'is_featured',
        'free_shipping',
        'return_in_stock',
        'commission'
    ];

    protected $casts = [
        'stock'         =>  'integer',
        'brand_id'      =>  'integer',
        'is_featured'   =>  'boolean',
        'free_shipping'   =>  'boolean'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'discount_start',
        'discount_end',
        'return_in_stock',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getImageAttribute()
    {
        $images = explode(',', $this->photo);
        if(count($images) > 0){
            return $images[0];
        }

        return 'https://e-marsa.s3.us-east-2.amazonaws.com/product-placeholder.jpg';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function order_product()
    {
        return $this->belongsToMany(OrderProduct::class, 'product_id', 'id');
    }

    /**
     * Get all of the collection for the product.
     */
    public function collections()
    {
        return $this->morphToMany(Collection::class, 'collectable');
    }

    // public function cat_info()
    // {
    //     return $this->hasOne('App\Models\Tenant\Category', 'id', 'cat_id');
    // }
    // public function sub_cat_info()
    // {
    //     return $this->hasOne('App\Models\Tenant\Category', 'id', 'child_cat_id');
    // }
    // public static function getAllProduct()
    // {
    //     return Product::with(['cat_info', 'sub_cat_info'])->orderBy('id', 'desc')->paginate(10);
    // }

    public function getHasActiveDiscountAttribute()
    {
        $now = Carbon::now();
        if($now->between($this->discount_start, $this->discount_end)){
            return true;
        }

        return false;
    }
    public function HasActiveDiscount()
    {
        $now = Carbon::now();
        if($now->between($this->discount_start, $this->discount_end)){
            return true;
        }

        return false;
    }

    public function relatedProducts($product)
    {
        $catIds = $product->categories->pluck('id')->toArray();
        $rels = Product::whereHas('categories', function($q) use ($catIds) {
            $q->whereIn('categories.id', $catIds);
        })
        ->take('7')
        ->get();

        return $rels;
    }

    public function getReview()
    {
        return $this->hasMany('App\Models\ProductReview', 'product_id', 'id')->with('user_info')->where('status', 'active')->orderBy('id', 'DESC');
    }

    public static function getProductBySlug($slug)
    {
        return Product::with(['categories', 'getReview'])->where('slug', $slug)->first();
    }

    public static function countActiveProduct()
    {
        $data = Product::where('status', 'active')->count();
        if ($data) {
            return $data;
        }
        return 0;
    }

    public function carts()
    {
        return $this->hasMany(Cart::class)->whereNotNull('order_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class)->whereNotNull('cart_id');
    }

    public function isNew(): bool
    {
        $date = Carbon::now()->subDays(3);
        if ($this->attributes['created_at'] > $date) {
            return true;
        }
        return false;
    }

    public function getCartQuantityAttribute()
    {
        return 0;
    }

    public function getSearchResult(): SearchResult
     {
        $url = route('admin.product.edit', $this->id);

         return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title,
            $url
         );
     }
}
