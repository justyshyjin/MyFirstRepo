<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateFollowPlaylistsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create ( 'follow_playlists', function (Blueprint $table) {
            $table->increments ( 'id' );
            $table->bigInteger ( 'customer_id' )->unsigned ();
            $table->foreign ( 'customer_id' )->references ( 'id' )->on ( 'customers' );
            $table->bigInteger ( 'playlist_id' )->unsigned ();
            $table->foreign ( 'playlist_id' )->references ( 'id' )->on ( 'playlists' )->onDelete ( 'cascade' );
            $table->timestamps ();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop ( 'follow_playlists' );
    }
}
