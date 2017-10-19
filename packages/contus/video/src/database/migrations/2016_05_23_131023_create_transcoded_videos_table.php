<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranscodedVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transcoded_videos', function (Blueprint $table) {
    		$table->bigIncrements('id');
    		$table->bigInteger('video_id');
    		$table->text('preset_details');
    		$table->text('video_url');
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
        Schema::drop ( 'transcoded_videos' );
    }
}
