<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solde extends Model
{
    use HasFactory;

    protected $fillable=['somme','date','description','provider_id','currency_id'];

    public function provider(){
        return $this->belongsTo(provider::class);
    }
    
}
