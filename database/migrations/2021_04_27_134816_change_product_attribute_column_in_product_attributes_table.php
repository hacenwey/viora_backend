<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProductAttributeColumnInProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->dropForeign('product_attributes_attribute_id_foreign');
            $table->dropForeign('product_attributes_product_id_foreign');
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade')
                ->change();
            $table->foreign('attribute_id')
                ->references('id')->on('attributes')
                ->onDelete('cascade')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_attributes', function (Blueprint $table) {
            //
        });
    }
}
