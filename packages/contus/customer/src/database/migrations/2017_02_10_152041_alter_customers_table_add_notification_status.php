<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomersTableAddNotificationStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('customers', function (Blueprint $table) {
            $table->tinyInteger('notification_status')->default(0)->after('access_token');

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
       $table->dropColumn('notification_status');
       });
    }
}
