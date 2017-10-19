<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPlaylsitOrderPlaylistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'playlists', function ($table) {
            $table->integer ( 'playlist_order' )->after ( 'is_active' )->default ( 0 );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::table ( 'playlists', function ($table) {
            $table->dropColumn ( 'playlist_order' );
        } );
    }
}
