<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlertOrdersTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `payment_method` ENUM('cod', 'paypal', 'bankily') NOT NULL;");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `payment_method` ENUM('cod', 'paypal') NOT NULL;");
    }
}
