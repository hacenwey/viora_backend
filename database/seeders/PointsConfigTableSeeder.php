<?php

namespace Database\Seeders;

use App\Modules\PointFidelite\Enums\eKeyPointConfig;
use App\Modules\PointFidelite\Enums\eTypePointConfig;
use App\Modules\PointFidelite\Enums\eUnitPointConfig;
use App\Modules\PointFidelite\Models\PointsConfig;
use Illuminate\Database\Seeder;


class PointsConfigTableSeeder extends Seeder
{
    public function run()
    {
        $configs = [
            [
                "title" => "Points expired time",
                "key" => eKeyPointConfig::POINTS_EXPIRED_TIME,
                "value" => 90,
                "type" => eTypePointConfig::CONFIG,
                "unit" => eUnitPointConfig::DAY
            ],
            [
                "title" => "Points equivalent to 1MRU",
                "key" => eKeyPointConfig::POINTS_TO_CASH,
                "value" => 40,
                "type" => eTypePointConfig::CONFIG,
                "unit" => null,
            ],
            [
                "title" => "Points earned after purchase 1MRU",
                "key" => eKeyPointConfig::CASH_TO_POINTS,
                "value" => 2,
                "type" => eTypePointConfig::CONFIG,
                "unit" => null,
            ],
            [
                "title" => "Minimun points",
                "key" => eKeyPointConfig::MIN_POINTS,
                "value" => 20,
                "type" => eTypePointConfig::CONFIG,
                "unit" => null,
            ],
            [
                "title" => "New account free points",
                "key" => eKeyPointConfig::NEW_ACCOUNT,
                "value" => 30,
                "type" => eTypePointConfig::FREE_POINT,
                "unit" => null,
            ],
        ];

        PointsConfig::insert($configs);
    }
}
