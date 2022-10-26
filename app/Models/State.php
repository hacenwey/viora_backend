<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Province;

class State extends Model
{
    use HasFactory;
    protected $table = "states";
    protected $fillable = [
        'name',
    ];



    /**
     * Get the user that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provinces()
    {
        return $this->hasMany(Province::class);
    }
}
