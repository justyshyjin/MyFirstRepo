<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('videos', function (Blueprint $table) {
    		$table->bigIncrements('id');
    		$table->bigInteger('category_id');
    		$table->string('title');
    		$table->string('slug');
    		$table->text('video_url');
    		$table->text('description');
    		$table->text('short_description');
    		$table->string('pipeline_id');
    		$table->string('job_id');
    		$table->string('job_status');
    		$table->text('thumbnail_image');
    		$table->text('preview_image');
    		$table->bigInteger('country_id');
    		$table->tinyInteger('is_featured')->default(0);
    		$table->tinyInteger('is_subscription')->default(0);
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
        Schema::drop ( 'videos' );
    }
}
