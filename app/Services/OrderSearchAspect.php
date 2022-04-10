<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Searchable\SearchAspect;

class OrderSearchAspect extends SearchAspect
{

    public $searchableType = 'Clients';

    public function getResults(string $term): Collection
    {
        return Order::where('phone', 'like', '%'.$term.'%')->get();
    }
}
