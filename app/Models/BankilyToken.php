<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankilyToken extends Model
{
    use HasFactory;
    protected $table = 'bankily_tokens';

    protected $fillable = [
        'acces_token'
    ];

}
