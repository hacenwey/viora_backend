<?php

namespace App\Modules\PointFidelite\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PointsConfig extends Model
{
    use HasFactory;

    protected $table = 'points_configs';
    protected $fillable = ['value'];
}
