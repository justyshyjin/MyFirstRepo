<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnCategeryIdPlaylistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table ( 'playlists', function (Blueprint $table) {
            $table->dropForeign ( 'playlists_category_id_foreign' );
            $table->dropColumn ( 'category_id' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table ( 'playlists', function (Blueprint $table) {
            $table->bigInteger ( 'category_id' )->unsigned ();
            $table->foreign ( 'category_id' )->references ( 'id' )->on ( 'categories' )->onDelete ( 'cascade' );
        } );
    }
}
