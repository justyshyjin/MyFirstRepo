<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsFeatureDemoTimeVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('videos', function($table){
           $table->integer('is_feature_time')->after('is_featured');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('videos', function($table){
           $table->dropColumn('is_feature_time');
         });
    }
}
