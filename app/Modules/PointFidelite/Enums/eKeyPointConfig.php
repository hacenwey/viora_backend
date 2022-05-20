<?php

namespace App\Modules\PointFidelite\Enums;
use App\Traits\EnumIterator;

 class eKeyPointConfig{
    use EnumIterator;

    const POINTS_DELAY_TIME = 'POINTS_DELAY_TIME';
    const MIN_POINTS = 'MIN_POINTS';
    const POINTS_COST = 'POINTS_COST';
    // 
    const NEW_ACCOUNT = 'NEW_ACCOUNT';
}
