<?php

namespace App\Models;

use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    /**
     * @var string
     */
    protected $table = 'attributes';

    protected $with = ['values'];

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'frontend_type',
        'is_filterable',
        'is_required'
    ];

    /**
     * @var array
     */
    protected $casts  = [
        'is_filterable' =>  'boolean',
        'is_required'   =>  'boolean',
    ];

    public function setIsFilterableAttribute($value)
    {
        return $this->attributes['is_filterable'] = isset($value) ? 1 : 0;
    }

    public function setIsRequiredAttribute($value)
    {
        return $this->attributes['is_required'] = isset($value) ? 1 : 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
