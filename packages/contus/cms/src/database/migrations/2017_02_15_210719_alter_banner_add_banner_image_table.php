<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBannerAddBannerImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('banners', function(Blueprint $table) {
            $table->longText('banner_image')->after('extension');
        });
    }

    /**
     * Reverse the migrations.
     * Banner image
     * @return void
     */
    public function down()
    {
          $table->dropColumn('banner_image');  
    }
}
