<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankilyTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankily_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('acces_token');
            $table->string('expires_in');
            $table->text('refresh_token');
            $table->string('refresh_expires_in');

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
        Schema::dropIfExists('bankily_tokens');
    }
}
