<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThumbUrlFieldToTranscodedVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcoded_videos', function(Blueprint $table) {
            $table->text('thumb_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('transcoded_videos', function(Blueprint $table) {
    		$table->dropColumn('thumb_url');
    	});
    }
}
