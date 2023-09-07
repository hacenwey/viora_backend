<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingIdToCitesTable extends Migration
{
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->decimal('price')->default(0);
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->foreign('shipping_id')
                  ->references('id')
                  ->on('shippings')
                  ->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['shipping_id']);
            $table->dropColumn('shipping_id');
        });
    }
}
