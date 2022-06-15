<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigneCommandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('arriving_time');
            $table->string('date');

            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->unsignedBigInteger('import_id');
            $table->foreign('import_id')->references('id')->on('imports');
            $table->unsignedBigInteger('command_id');
            $table->foreign('command_id')->references('id')->on('commandes');
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
        Schema::dropIfExists('ligne_commandes');
    }
}
