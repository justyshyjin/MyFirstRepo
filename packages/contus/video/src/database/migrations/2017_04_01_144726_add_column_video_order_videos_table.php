<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnVideoOrderVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'videos', function ($table) {
            $table->integer ( 'video_order' )->after ( 'is_active' )->default ( 0 );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table ( 'videos', function ($table) {
            $table->dropColumn ( 'video_order' );
        } );
    }
}
