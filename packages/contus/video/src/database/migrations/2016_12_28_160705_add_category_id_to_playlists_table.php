<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AddCategoryIdToPlaylistsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table ( 'playlists', function (Blueprint $table) {
            $table->bigInteger ( 'category_id' )->unsigned ()->after('slug');
            $table->foreign ( 'category_id' )->references ( 'id' )->on ( 'categories' )->onDelete ( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table ( 'playlists', function (Blueprint $table) {
            $table->dropColumn ( 'category_id' );
        } );
    }
}
