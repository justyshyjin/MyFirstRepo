<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreatePaymentTransactionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create ( 'payment_transactions', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->bigInteger ( 'payment_method_id' )->unsigned ();
            $table->bigInteger ( 'customer_id' )->unsigned ();
            $table->string('status',50);
            $table->text ( 'transaction_message' );
            $table->string('transaction_id',255);
            $table->json('response');
            $table->bigInteger ( 'creator_id' )->default ( 0 );
            $table->bigInteger ( 'updator_id' )->default ( 0 );
            $table->timestamps ();
        } );

        Schema::table ( 'payment_transactions', function ($table) {
            $table->foreign ( 'payment_method_id' )->references ( 'id' )->on ( 'payment_methods' )->onDelete ( 'cascade' );
            $table->foreign ( 'customer_id' )->references ( 'id' )->on ( 'customers' )->onDelete ( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop ( 'payment_transactions' );
    }
}
