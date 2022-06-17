<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Currency;
class Provider extends Model
{
    use HasFactory;
    protected $fillable=['name','email','phone','currency_id'];

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
}
