<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSubscriptionPlanIdSubscriberIdPaymentTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'payment_transactions', function (Blueprint $table) {
            $table->bigInteger ( 'subscription_plan_id' )->nullable ()->after('response');
            $table->bigInteger ( 'subscriber_id' )->nullable ()->after('response');
 
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table ( 'payment_transactions', function (Blueprint $table) {
            $table->dropColumn ( 'subscription_plan_id' );
            $table->dropColumn ( 'subscriber_id' );
        } );
    }
}
