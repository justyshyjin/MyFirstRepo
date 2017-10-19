<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVideosTableAddLiveStatusAndYoutubePrivacy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'videos', function (Blueprint $table) {
            $table->string ( 'liveStatus')->after ( 'totalResults' );
            $table->string ( 'youtubePrivacy')->after ( 'liveStatus' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table ( 'videos', function (Blueprint $table) {
            $table->dropColumn ( 'liveStatus' );
            $table->dropColumn ( 'youtubePrivacy' );
        } );
    }
}
