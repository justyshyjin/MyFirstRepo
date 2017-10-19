<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageFieldsToVideoCastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_cast', function(Blueprint $table) {
            $table->string("image_url")->nullable();
            $table->string("image_path")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_cast', function(Blueprint $table) {
            $table->dropColumn('image_url');
            $table->dropColumn('image_path');
        });
    }
}
