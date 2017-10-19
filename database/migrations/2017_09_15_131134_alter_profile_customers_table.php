<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {    
          Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->text('profile_picture')->after('access_token');
            $table->string('device_type')->after('access_token');
            $table->text('device_token')->after('access_token');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->text('avatar');
            $table->dropColumn('profile_picture');
            $table->dropColumn('device_type');
            $table->dropColumn('device_token');
        });
    }
}
