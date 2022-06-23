<?php

namespace App\Modules\PointFidelite\Enums;
use App\Traits\EnumIterator;

 class eKeyPointConfig{
    use EnumIterator;

    const POINTS_EXPIRED_TIME = 'POINTS_EXPIRED_TIME';
    const MIN_POINTS = 'MIN_POINTS'; // min points needed to buy a item
    const POINTS_TO_CASH = 'POINTS_TO_CASH'; // : how much points needed to get 1MRU !!  
    const CASH_TO_POINTS = 'CASH_TO_POINTS'; // : number of points earned after a purchase of 1MRU !!
    // 
    const NEW_ACCOUNT = 'NEW_ACCOUNT'; // free point gived to new account
}
