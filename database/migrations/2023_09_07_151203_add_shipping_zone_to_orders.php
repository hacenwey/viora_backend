<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingZoneToOrders extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shippingSelectdZone')->nullable();
            $table->float('shippingSelectdZonePrice')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shippingSelectdZone');
            $table->dropColumn('shippingSelectdZonePrice');
        });
    }
}
