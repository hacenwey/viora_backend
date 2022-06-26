<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplyOrdersItemsTable extends Migration
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
            $table->integer('qte');
            $table->float('purchase_price')->nullable();
            $table->float('provider_expenses')->nullable();
            $table->float('local_expenses')->nullable();
            $table->boolean('selected')->default(0);
            $table->unsignedBigInteger('supply_item_id')->nullable();
            $table->foreign('supply_item_id')->references('id')->on('supply_items');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->float('particular_exchange')->nullable();
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
