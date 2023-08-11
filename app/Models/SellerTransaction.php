<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SellerTransaction extends Model
{
    use HasFactory;
    protected $table = 'sellers_transactions';

    protected $fillable = [
        'id',
        'reference',
        'solde',
        'seller_id',
        'order_id',
        'type',
    ];


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->reference = 'TR-' . strtoupper(uniqid()); 
        });
    }


    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id','id');
    }
 
}
