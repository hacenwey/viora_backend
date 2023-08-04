<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('order_id');
            $table->enum('status',['new','process','delivered','paid','cancel'])->default('new');

            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');  
            $table->foreign('order_id')->references('id')->on('orders');      
        });



        Schema::create('sellers_order_products', function (Blueprint $table) {
            $table->unsignedBigInteger('sellers_order_id');
            $table->unsignedBigInteger('product_id');
            $table->float('price');
            $table->integer('quantity');
            $table->float('sub_total');
            $table->string('commission');
            $table->float('gain');


            $table->timestamps();

            $table->foreign('sellers_order_id')->references('id')->on('sellers_orders')->onDelete('CASCADE');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sellers_orders');
        Schema::dropIfExists('sellers_order_products');

    }
}
