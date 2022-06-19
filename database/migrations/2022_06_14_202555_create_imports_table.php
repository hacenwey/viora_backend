<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['IN_PROGRESS', 'DONE', 'FAILED'])->default('IN_PROGRESS');
            $table->string('journal_duration');
            $table->string('duration');
            $table->string('file_name');
            $table->json('failed_skus')->nullable();
            $table->timestamps();
        });
    }

    /**duration of provisions
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imports');
    }
}
