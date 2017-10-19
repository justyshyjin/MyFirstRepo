<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSubscriptionPlanNamePaymentTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table ( 'payment_transactions', function (Blueprint $table) {
            $table->string( 'plan_name' )->nullable ()->after('response');
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
             $table->dropColumn ( 'plan_name' );
        } );
    }
}
