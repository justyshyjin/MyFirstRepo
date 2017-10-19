<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateSubscribersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create ( 'subscribers', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->bigInteger ( 'subscription_plan_id' )->unsigned ();
            $table->bigInteger ( 'customer_id' )->unsigned ();
            $table->date ( 'start_date' );
            $table->date ( 'end_date' );
            $table->tinyInteger ( 'is_active' )->default ( 0 );
            $table->bigInteger ( 'creator_id' )->default ( 0 );
            $table->bigInteger ( 'updator_id' )->default ( 0 );
            $table->timestamps ();
            $table->foreign('subscription_plan_id')->references ( 'id' )->on ( 'subscription_plans' )->onDelete ( 'cascade' );
            $table->foreign('customer_id')->references ( 'id' )->on ( 'customers' )->onDelete ( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop ( 'subscribers' );
    }
}
