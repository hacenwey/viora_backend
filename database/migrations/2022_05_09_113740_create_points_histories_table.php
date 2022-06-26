<?php

use App\Modules\PointFidelite\Enums\eCreatedVia;
use App\Modules\PointFidelite\Enums\eTypePointHistory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            //
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('CASCADE');
            //
            $table->enum('type', eTypePointHistory::getAll(eTypePointHistory::class));
            $table->integer('prev_solde');
            $table->integer('point');
            $table->integer('solde');
            //
            $table->enum('created_via' , eCreatedVia::getAll(eCreatedVia::class))->default(eCreatedVia::FO);
            $table->string('created_by')->nullable();
            $table->timestamp('expired_at')->nullable();
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
        Schema::dropIfExists('points_histories');
    }
}
