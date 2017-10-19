<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLatestNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('latest_news', function (Blueprint $table) {
        	$table->increments('id');
        	$table->string('title',255);
        	$table->string('slug',255);
        	$table->longText('content');
        	$table->tinyInteger('is_active')->default(0);
        	$table->bigInteger('creator_id')->default(0);
        	$table->bigInteger('updator_id')->default(0);
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
        Schema::drop('latest_news');
    }
}
