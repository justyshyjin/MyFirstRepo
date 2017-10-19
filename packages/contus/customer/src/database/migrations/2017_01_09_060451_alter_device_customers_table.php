<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeviceCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('customers', function (Blueprint $table) {
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
         $table->dropColumn('device_type');
         $table->dropColumn('device_token');
       });
    }
}
