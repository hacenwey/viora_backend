<?php

use App\Modules\PointFidelite\Enums\eKeyPointConfig;
use App\Modules\PointFidelite\Enums\eTypePointConfig;
use App\Modules\PointFidelite\Enums\eUnitPointConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_configs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('key', eKeyPointConfig::getAll(eKeyPointConfig::class));
            $table->string('value');
            $table->enum('type', eTypePointConfig::getAll(eTypePointConfig::class));
            $table->enum('unit', eUnitPointConfig::getAll(eUnitPointConfig::class))->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points_configs');
    }
}
