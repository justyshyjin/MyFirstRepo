<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRecentlyViewedVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    Schema::create ( 'recently_viewed_videos', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->bigInteger ( 'video_id' )->unsigned ();
            $table->bigInteger ( 'customer_id' )->unsigned ();
            $table->timestamps ();
            $table->foreign ( 'customer_id' )->references ( 'id' )->on ( 'customers' )->onDelete ( 'cascade' );
            $table->foreign ( 'video_id' )->references ( 'id' )->on ( 'videos' )->onDelete ( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ( 'recently_viewed_videos' );
    }
}
