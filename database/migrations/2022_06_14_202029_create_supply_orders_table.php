<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply-orders', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['CONFIRMEE', 'EN_ROUTE' ,'PARTIALLY_SHIPPED', 'SHIPPED', 'ARCHIVED'])->default('CONFIRMEE');
            $table->string('arriving_time')->nullable();
            $table->string('shipping_cost')->nullable();

            $table->float('provider_expenses')->nullable();
            $table->float('local_expenses')->nullable();

            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers');
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
        Schema::dropIfExists('supply-orders');
    }
}
