<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AlterRelationCollectionIdToGroupId extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table ( 'collections_videos', function (Blueprint $table) {
            $table->dropForeign ( 'collections_videos_collection_id_foreign' );
            $table->dropColumn ( 'collection_id' );
            $table->dropColumn ( 'category_id' );
            $table->bigInteger ( 'group_id' )->unsigned ()->after ( 'video_id' );
            $table->foreign ( 'group_id' )->references ( 'id' )->on ( 'groups' )->onDelete ( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table ( 'collections_videos', function (Blueprint $table) {
            $table->bigInteger ( 'category_id' )->unsigned ();
            $table->bigInteger ( 'collection_id' )->unsigned ();
            $table->foreign ( 'collection_id' )->references ( 'id' )->on ( 'collections' )->onDelete ( 'cascade' );
            $table->dropForeign ( 'collections_videos_group_id_foreign' );
            $table->dropColumn ( 'group_id' );
        } );
    }
}
