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
                "key" => eKeyPointConfig::POINTS_DELAY_TIME,
                "value" => 90,
                "type" => eTypePointConfig::CONFIG,
                "unit" => eUnitPointConfig::DAY
            ],
            [
                "key" => eKeyPointConfig::POINTS_COST,
                "value" => 10,
                "type" => eTypePointConfig::CONFIG,
                "unit" => null,
            ],
            [
                "key" => eKeyPointConfig::MIN_POINTS,
                "value" => 20,
                "type" => eTypePointConfig::CONFIG,
                "unit" => null,
            ],
            [
                "key" => eKeyPointConfig::NEW_ACCOUNT,
                "value" => 30,
                "type" => eTypePointConfig::FREE_POINT,
                "unit" => null,
            ],
        ];

        PointsConfig::insert($configs);
    }
}
