<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'email',
        'created_at',
        'updated_at',
    ];

    public static function isSubscribed($email)
    {
        if(NewsLetter::where('email', $email)->exists()){
            return true;
        }else{
            return false;
        }
    }
}
