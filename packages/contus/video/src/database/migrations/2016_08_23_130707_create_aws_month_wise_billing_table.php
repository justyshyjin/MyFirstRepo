<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwsMonthWiseBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aws_month_wise_billing', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->string("billing_year");
            $table->string("billing_month");
            $table->float("total_cost");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('aws_month_wise_billing');
    }
}
