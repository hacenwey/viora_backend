<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable=[
        'user_id',
        'product_id',
        'rate',
        'title',
        'review',
        'name',
        'email',
        'status'
    ];

    public function user_info(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public static function getAllReview(){
        return ProductReview::with('user_info')->paginate(10);
    }
    public static function getAllUserReview(){
        return ProductReview::where('user_id',Auth::guard()->user()->id)->with('user_info')->paginate(10);
    }

    // public function getCreatedAtAttribute($value)
    // {
    //     return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('M d D, Y g: i a') : null;
    // }

}
