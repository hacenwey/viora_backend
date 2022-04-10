<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use App\Notifications\StoreResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class User extends Authenticatable implements Searchable
{
    use HasApiTokens, Notifiable;

    public $searchableType = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'email_verified_at',
        'photo',
        'status',
        'provider',
        'provider_id',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders(){
        return $this->hasMany('App\Models\Order', 'user_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('title', 'Admin')->exists();
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new StoreResetPasswordNotification($token));
    }

    public function getSearchResult(): SearchResult
     {
        $url = route('backend.clients.show', [$this->id, 'phone' => $this->phone ?? $this->phone_number, 'id' => $this->id]);

         return new \Spatie\Searchable\SearchResult(
            $this,
            $this->first_name.' '.$this->last_name.' | '.$this->phone_number,
            $url
         );
     }

}
