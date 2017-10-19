<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotificationsAlertCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('customers', function (Blueprint $table) {
            $table->tinyInteger('notify_comment')->default(0)->after('access_token');
            $table->tinyInteger('notify_reply_comment')->default(0)->after('access_token');
            $table->tinyInteger('notify_videos')->default(0)->after('access_token');
            $table->tinyInteger('notify_newsletter')->default(0)->after('access_token');

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
        $table->dropColumn('notify_comment');
        $table->dropColumn('notify_reply_comment');
        $table->dropColumn('notify_videos');
        $table->dropColumn('notify_newsletter');
       });
    }
}
