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
        'request_payload',
        'response_data',
        'ip_address',
        'user_agent'

    ];
 
}
