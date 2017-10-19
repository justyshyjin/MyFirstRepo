<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateVideoPlaylistsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create ( 'video_playlists', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->bigInteger ( 'playlist_id' )->unsigned ();
            $table->bigInteger ( 'video_id' )->unsigned ();
            $table->tinyInteger ( 'is_active' )->default ( 0 );
            $table->bigInteger ( 'creator_id' )->default ( 0 );
            $table->bigInteger ( 'updator_id' )->default ( 0 );
            $table->timestamps ();
        } );

        Schema::table ( 'video_playlists', function ($table) {
            $table->foreign ( 'playlist_id' )->references ( 'id' )->on ( 'playlists' )->onDelete ( 'cascade' );
            $table->foreign ( 'video_id' )->references ( 'id' )->on ( 'videos' )->onDelete ( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop ( 'video_playlists' );
    }
}
