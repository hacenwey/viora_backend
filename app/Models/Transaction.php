<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'operationId',
        'clientPhone',
        'amount',
        'errorCode',
        'errorMessage',
        'transactionId',
        'order_id'

    ];
 
}
