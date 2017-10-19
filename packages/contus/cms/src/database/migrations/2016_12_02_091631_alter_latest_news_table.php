<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLatestNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('latest_news', function(Blueprint $table) {
            $table->string('post_creator',100)->after('content');
            $table->string('post_image')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('latest_news', function(Blueprint $table) {
            $table->dropColumn('post_creator');
            $table->string('post_image');
        });
    }
}
