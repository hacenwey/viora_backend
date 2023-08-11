<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('solde');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->enum('type',['IN','OUT']);

            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');  

            $table->foreign('order_id')->references('id')->on('orders');      

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
        Schema::dropIfExists('sellers_transactions');
    }
}
