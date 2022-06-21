<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{

    /**
     * @var string
     */
    protected $table = 'currencies';

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'status',
        'toux_change',
        'created_at',
        'updated_at'
    ];

}
