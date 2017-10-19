<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AddVideoDurationInVideosTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table ( 'videos', function (Blueprint $table) {
            $table->char ( 'video_duration' )->default ( '0:00' )->after ( 'short_description' );
            $table->boolean ( 'is_hls' );
            $table->text ( 'hls_playlist_url' )->after ( 'short_description' );
            $table->text ( 'aws_prefix' )->after ( 'hls_playlist_url' );
            $table->text ( 'selected_thumb' )->after ( 'hls_playlist_url' );
            $table->boolean( 'youtube_live' )->after ( 'job_status' );
            $table->string ( 'youtube_id' )->after ( 'youtube_live' );
            $table->string ( 'scheduledStartTime')->after ( 'youtube_id' );
            $table->string ( 'nextPageToken')->after ( 'scheduledStartTime' );
            $table->string ( 'totalResults')->after ( 'nextPageToken' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table ( 'videos', function (Blueprint $table) {
            $table->dropColumn ( 'selected_thumb' );
            $table->dropColumn ( 'aws_prefix' );
            $table->dropColumn ( 'hls_playlist_url' );
            $table->dropColumn ( 'is_hls' );
            $table->dropColumn ( 'video_duration' );
            $table->dropColumn( 'youtube_live' );
            $table->dropColumn ( 'youtube_id' );
            $table->dropColumn ( 'scheduledStartTime');
            $table->dropColumn ( 'nextPageToken');
            $table->dropColumn ( 'totalResults');
        } );
    }
}
