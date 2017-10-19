<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoPresetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_presets', function (Blueprint $table) {
    		$table->bigIncrements('id');
    		$table->string('name');
    		$table->string('aws_id');
    		$table->string('format');
    		$table->text('description');
    		$table->tinyInteger('is_active')->default(0);
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
        Schema::drop ( 'video_presets' );
    }
}
