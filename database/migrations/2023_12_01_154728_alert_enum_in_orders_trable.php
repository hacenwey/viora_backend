<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertEnumInOrdersTrable extends Migration
{
    public function up()
    { 
        // #TODDO, pour ajouter un text explicatif plus tard pour cod.
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `payment_method` ENUM('cod', 'paypal', 'bankily','emwali') NOT NULL;");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `payment_method` ENUM('cod', 'paypal','bankily') NOT NULL;");
    }
}
