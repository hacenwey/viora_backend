<?php

namespace App\Models;

use TypiCMS\NestableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class SubCategory extends Model 
{
    
    protected $fillable = ['title', 'slug', 'summary', 'photo', 'status', 'is_parent', 'parent_id', 'added_by'];

    
}
