<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPresetIdToTranscodedVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcoded_videos', function(Blueprint $table) {
            $table->bigInteger('preset_id');
            $table->dropColumn('preset_details');
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
        	$table->text('preset_details');
    		$table->dropColumn('preset_id');
    	});
    }
}
