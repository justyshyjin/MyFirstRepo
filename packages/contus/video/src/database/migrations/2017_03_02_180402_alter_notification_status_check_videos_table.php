<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotificationStatusCheckVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'videos', function (Blueprint $table) {
            $table->tinyInteger( 'notification_status' )->default (0)->after ( 'is_active' );
          
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
            $table->dropColumn('notification_status')->default (0)->after ( 'is_active' );
          
        } );
    }
}
