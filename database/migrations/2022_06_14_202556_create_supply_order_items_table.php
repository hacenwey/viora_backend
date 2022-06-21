<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplyOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supply_order_items', function (Blueprint $table) {
            $table->id();
            //$table->string('status');
            //$table->string('arriving_time');
            $table->integer('qte');
            $table->unsignedBigInteger('import_id');
            $table->foreign('import_id')->references('id')->on('imports');

            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('providers');

            $table->boolean('selected')->default(0);
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');


            // order id
            $table->unsignedBigInteger('supply_order_id')->nullable();
            $table->foreign('supply_order_id')->references('id')->on('supply-orders');
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
        Schema::dropIfExists('supply_order_items');
    }
}
