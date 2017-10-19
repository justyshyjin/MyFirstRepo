<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWatchLatersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watch_laters', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('video_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->timestamps();
            $table->foreign('video_id')->references('id')->on('videos');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('watch_laters', function (Blueprint $table) {
            Schema::drop('watch_laters');
        });
    }
}
