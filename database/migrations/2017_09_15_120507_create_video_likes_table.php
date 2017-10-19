<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('video_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('like_count');
            $table->bigInteger('dislike_count');
            $table->bigInteger('views_count');
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
        Schema::drop('video_likes');
    }
}
