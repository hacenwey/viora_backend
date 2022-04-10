<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    /**
     * @var string
     */
    protected $table = 'product_attributes';
    protected $appends = ['selected'];

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'attribute_id',
        'value',
        'stock',
        'price',
        'is_required'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_required'   =>  'boolean',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributesValues()
    {
        return $this->belongsToMany(AttributeValue::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }


    public function setIsRequiredAttribute($value)
    {
        return $this->attributes['is_required'] = isset($value) ? 1 : 0;
    }

    public function getSelectedAttribute()
    {
        return false;
    }
}
