<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThumbnailFormatFieldToVideoPresetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_presets', function(Blueprint $table) {
            $table->string('thumbnail_format');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_presets', function(Blueprint $table) {
    		$table->dropColumn('thumbnail_format');
    	});
    }
}
