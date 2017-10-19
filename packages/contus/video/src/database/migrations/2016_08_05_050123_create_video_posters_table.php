<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoPostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_posters', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->bigInteger('video_id')->unsigned();
            $table->string("image_url")->nullable();
            $table->string("image_path")->nullable();
            $table->timestamps();
            $table->foreign ( 'video_id' )->references ( 'id' )->on ( 'videos' )->onDelete ( 'cascade' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('video_posters');
    }
}
