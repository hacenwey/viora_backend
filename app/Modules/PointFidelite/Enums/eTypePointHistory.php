<?php

namespace App\Modules\PointFidelite\Enums;
use App\Traits\EnumIterator;

 class eTypePointHistory{
    use EnumIterator;

    const IN = 'IN';
    const OUT = 'OUT';
    const EXPIRED = 'EXPIRED';
}
