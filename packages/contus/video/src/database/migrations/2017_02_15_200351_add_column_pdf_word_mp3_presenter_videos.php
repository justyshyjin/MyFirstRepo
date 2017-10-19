<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPdfWordMp3PresenterVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'videos', function (Blueprint $table) {
            $table->string ( 'presenter')->after ( 'liveStatus' );
            $table->string ( 'pdf')->after ( 'presenter' );
            $table->string ( 'word')->after ( 'pdf' );
            $table->string ( 'mp3')->after ( 'word' );
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
            $table->dropColumn ( 'mp3' );
            $table->dropColumn ( 'word' );
            $table->dropColumn ( 'pdf' );
            $table->dropColumn ( 'presenter' );
        } );
    }
}
